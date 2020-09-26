<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\MessageBundle\FormType\ModerateMessageFormType;
use FOS\MessageBundle\Model\MessageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ModerateMessageController
 */
class ModerateMessageController extends AbstractController
{
    /**
     * @var string
     */
    private $messageEntityClass;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * ModerateMessageController constructor.
     * @param string $messageEntityClass
     * @param string $templatePath
     */
    public function __construct(string $messageEntityClass, string $templatePath)
    {
        $this->messageEntityClass = $messageEntityClass;
        $this->templatePath       = $templatePath;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $messageId = $request->get('id');

        /**
         * @var MessageInterface $message
         */
        $message = $entityManager->find($this->messageEntityClass, $messageId);

        if ($message !== null) {
            $form = $this->createForm(
                ModerateMessageFormType::class,
                [
                    ModerateMessageFormType::FORM_CHILD_IS_APPROVED => $message->getIsModerated()
                ]
            );

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $data = $form->getData();

                    $entityManager->beginTransaction();

                    try {
                        $message->setIsModerated((bool) $data[ModerateMessageFormType::FORM_CHILD_IS_APPROVED]);
                        $entityManager->persist($message);

                        $entityManager->flush();
                        $entityManager->commit();

                        $this->addFlash('success', 'Message was successfully updated!');
                    } catch (Exception $e) {
                        $entityManager->rollback();

                        throw $e;
                    }
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
