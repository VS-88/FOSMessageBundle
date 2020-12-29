<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Exceptions\SubmittedMessageValidationException;
use FOS\MessageBundle\FormFactory\AbstractMessageFormFactory;
use FOS\MessageBundle\FormHandler\AbstractMessageFormHandler;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ThreadViewController
 */
class ThreadViewController extends AbstractProviderAwareController
{
    /**
     * @var AbstractMessageFormFactory
     */
    private $replyMessageFormFactory;

    /**
     * @var AbstractMessageFormHandler
     */
    private $replyFormHandler;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * ThreadViewController constructor.
     *
     * @param ProviderInterface $provider
     * @param string $messageInboxTemplatePath
     * @param AbstractMessageFormFactory $replyMessageFormFactory
     * @param AbstractMessageFormHandler $newFormHandler
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        ProviderInterface $provider,
        string $messageInboxTemplatePath,
        AbstractMessageFormFactory $replyMessageFormFactory,
        AbstractMessageFormHandler $newFormHandler,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        parent::__construct($provider, $messageInboxTemplatePath);

        $this->replyMessageFormFactory = $replyMessageFormFactory;
        $this->replyFormHandler        = $newFormHandler;
        $this->authorizationChecker    = $authorizationChecker;
    }

    /**
     * Displays a thread, also allows to reply to it.
     *
     * @param Request $request
     * @param int $threadId the thread id
     *
     * @return Response
     */
    public function indexAction(Request $request, int $threadId): Response
    {
        $thread      = $this->provider->getThreadAndMarkAsRead($threadId);

        if ($thread !== null) {
            if ($this->authorizationChecker->isGranted('VIEW', $thread) === false) {
                throw $this->createAccessDeniedException();
            }
        } else {
            $this->createNotFoundException();
        }

        $form        = $this->replyMessageFormFactory->create($thread);

        try {
            if ($message = $this->replyFormHandler->process($form, $request)) {
                $this->addFlash('success', 'Message was successfully created!');

                $resp =  $this->redirectToRoute('fos_message_thread_view', [
                    'threadId' => $message->getThread()->getId(),
                ]);
            } else {
                $resp = $this->render($this->templatePath, [
                    'form' => $form->createView(),
                    'thread' => $thread,
                ]);
            }
        } catch (SubmittedMessageValidationException $exception) {
            /**
             * @var FormError $error
             */
            foreach ($exception->getFormErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            $resp = $this->render($this->templatePath, [
                'form' => $form->createView(),
                'thread' => $thread,
            ]);
        }


        return $resp;
    }
}
