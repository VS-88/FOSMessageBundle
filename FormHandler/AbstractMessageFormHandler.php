<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\FormHandler;

use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles messages forms, from binding request to sending the message.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class AbstractMessageFormHandler
{
    /**
     * @var ComposerInterface
     */
    protected $composer;

    /**
     * @var SenderInterface
     */
    protected $sender;

    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * @param ComposerInterface            $composer
     * @param SenderInterface              $sender
     * @param ParticipantProviderInterface $participantProvider
     */
    public function __construct(
        ComposerInterface $composer,
        SenderInterface $sender,
        ParticipantProviderInterface $participantProvider
    ) {
        $this->composer = $composer;
        $this->sender = $sender;
        $this->participantProvider = $participantProvider;
    }

    /**
     * Processes the form with the request.
     *
     * @param FormInterface $form
     *
     * @param Request $request
     *
     * @return MessageInterface|null the sent message if the form is bound and valid, false otherwise
     */
    public function process(FormInterface $form, Request $request): ?MessageInterface
    {
        if ($request->getMethod() !== 'POST') {
            return null;
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $message = $this->composeMessage($form->getData());
            $this->sender->send($message);

            return $message;
        }

        return null;
    }

    /**
     * Composes a message from the form data.
     *
     * @param AbstractMessage $message
     *
     * @return MessageInterface the composed message ready to be sent
     */
    abstract protected function composeMessage(AbstractMessage $message): MessageInterface;

    /**
     * Gets the current authenticated user.
     *
     * @return ParticipantInterface
     */
    protected function getAuthenticatedParticipant(): ParticipantInterface
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }
}
