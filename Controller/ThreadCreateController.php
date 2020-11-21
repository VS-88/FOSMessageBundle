<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Exceptions\SubmittedMessageValidationException;
use FOS\MessageBundle\FormFactory\AbstractMessageFormFactory;
use FOS\MessageBundle\FormHandler\AbstractMessageFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThreadCreateController
 */
class ThreadCreateController extends AbstractController
{
    /**
     * @var AbstractMessageFormFactory
     */
    private $newMessageFormFactory;

    /**
     * @var AbstractMessageFormHandler
     */
    private $newFormHandler;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * ThreadCreateController constructor.
     *
     * @param string $messageInboxTemplatePath
     * @param AbstractMessageFormFactory $newMessageFormFactory
     * @param AbstractMessageFormHandler $newFormHandler
     */
    public function __construct(
        string $messageInboxTemplatePath,
        AbstractMessageFormFactory $newMessageFormFactory,
        AbstractMessageFormHandler $newFormHandler
    ) {
        $this->templatePath = $messageInboxTemplatePath;
        $this->newMessageFormFactory = $newMessageFormFactory;
        $this->newFormHandler        = $newFormHandler;
    }

    /**
     * Create a new message thread.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $form = $this->newMessageFormFactory->create();

        try {
            if ($message = $this->newFormHandler->process($form, $request)) {
                $this->addFlash('success', 'Message was successfully created!');

                $resp =  $this->redirectToRoute('fos_message_thread_new');
            } else {
                $resp = $this->render($this->templatePath, [
                    'form' => $form->createView(),
                    'data' => $form->getData(),
                ]);
            }
        } catch (SubmittedMessageValidationException $exception) {
            /**
             * @var FormError $error
             */
            foreach ($exception->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            $resp = $this->render($this->templatePath, [
                'form' => $form->createView(),
                'data' => $form->getData(),
            ]);
        }

        return $resp;
    }
}
