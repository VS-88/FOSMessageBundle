<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Provider;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Reader\ReaderInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides threads for the current authenticated user.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Provider implements ProviderInterface
{
    /**
     * The thread manager.
     *
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * The message manager.
     *
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * The reader used to mark threads as read.
     *
     * @var ReaderInterface
     */
    protected $threadReader;

    /**
     * The authorizer manager.
     *
     * @var authorizerInterface
     */
    protected $authorizer;

    /**
     * The participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * Provider constructor.
     * @param ThreadManagerInterface $threadManager
     * @param MessageManagerInterface $messageManager
     * @param ReaderInterface $threadReader
     * @param AuthorizerInterface $authorizer
     * @param ParticipantProviderInterface $participantProvider
     */
    public function __construct(
        ThreadManagerInterface $threadManager,
        MessageManagerInterface $messageManager,
        ReaderInterface $threadReader,
        AuthorizerInterface $authorizer,
        ParticipantProviderInterface $participantProvider
    ) {
        $this->threadManager = $threadManager;
        $this->messageManager = $messageManager;
        $this->threadReader = $threadReader;
        $this->authorizer = $authorizer;
        $this->participantProvider = $participantProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getInboxThreads(): array
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->threadManager->findParticipantInboxThreads($participant);
    }

    /**
     * {@inheritdoc}
     */
    public function getSentThreads(): array
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->threadManager->findParticipantSentThreads($participant);
    }

    /**
     * {@inheritdoc}
     */
    public function getDeletedThreads(): array
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->threadManager->findParticipantDeletedThreads($participant);
    }

    /**
     * NOTICE: Inside $em->clear() is called.
     * If you want persist it, for example, use $em->refresh(...) method before
     *
     * {@inheritdoc}
     */
    public function getThreadAndMarkAsRead($threadId): ?ThreadInterface
    {
        $thread = $this->threadManager->findThreadById($threadId);

        if (!$thread) {
            throw new NotFoundHttpException('There is no such thread');
        }

        // Load the thread messages before marking them as read
        // because we want to see the unread messages
        $thread->getMessages();
        $this->threadReader->markAsRead($thread);

        return $thread;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbUnreadMessages(): int
    {
        return $this->messageManager->getNbUnreadMessageByParticipant($this->getAuthenticatedParticipant());
    }

    /**
     * Gets the current authenticated user.
     *
     * @return ParticipantInterface
     */
    protected function getAuthenticatedParticipant(): ParticipantInterface
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }

    public function findThreadById(int $id): ThreadInterface
    {
        return $this->threadManager->findThreadById($id);
    }
}
