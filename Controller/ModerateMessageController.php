<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\MessageBundle\Event\ModeratedMessageEvent;
use FOS\MessageBundle\FormType\ModerateMessageFormType;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Traits\TranslatorAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ModerateMessageController
 */
class ModerateMessageController extends AbstractController
{
    use TranslatorAwareTrait;

    /**
     * @var string
     */
    private $messageEntityClass;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * ModerateMessageController constructor.
     * @param string $messageEntityClass
     * @param string $templatePath
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        string $messageEntityClass,
        string $templatePath,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->messageEntityClass = $messageEntityClass;
        $this->templatePath       = $templatePath;
        $this->eventDispatcher    = $eventDispatcher;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     *
     * @param int $messageId
     * @return Response
     * @throws Exception
     */
    public function indexAction(
        EntityManagerInterface $entityManager,
        Request $request,
        int $messageId
    ): Response {
        /**
         * @var MessageInterface $message
         */
        $message = $entityManager->find($this->messageEntityClass, $messageId);

        if ($message !== null) {
            $form = $this->createForm(
                ModerateMessageFormType::class
            );

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $entityManager->beginTransaction();

                    $isApproved = $form->get(ModerateMessageFormType::FORM_CHILD_APPROVE)->isClicked()
                        ? true
                        : false;

                    $message->setIsModerated($isApproved);

                    try {
                        $entityManager->persist($message);

                        $entityManager->flush();

                        $entityManager->commit();
                    } catch (Exception $e) {
                        $entityManager->rollback();

                        throw $e;
                    }

                    $this->addFlash(
                        'success',
                        $this->translate('Message was successfully updated!', [], 'FOSMessageBundle')
                    );

                    $this->eventDispatcher->dispatch(new ModeratedMessageEvent($message));
                } else {

                    /**
                     * @var FormError $error
                     */
                    foreach ($form->getErrors() as $error) {
                        $this->addFlash('error', $error->getMessage());
                    }
                }
            }

            $response =  $this->render($this->templatePath, [
                'form'     => $form->createView(),
                'message' => $message,
            ]);
        } else {
            throw $this->createNotFoundException('Message does not exist!');
        }

        return $response;
    }
}
