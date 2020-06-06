<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Deleter;

use FOS\MessageBundle\Event\FOSMessageEvents;
use FOS\MessageBundle\Event\ThreadEvent;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Marks threads as deleted.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Deleter implements DeleterInterface
{
    /**
     * The authorizer instance.
     *
     * @var AuthorizerInterface
     */
    private $authorizer;

    /**
     * The participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    private $participantProvider;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Deleter constructor.
     * @param AuthorizerInterface $authorizer
     * @param ParticipantProviderInterface $participantProvider
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        AuthorizerInterface $authorizer,
        ParticipantProviderInterface $participantProvider,
        EventDispatcherInterface $dispatcher
    ) {
        $this->authorizer = $authorizer;
        $this->participantProvider = $participantProvider;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function markAsDeleted(ThreadInterface $thread): void
    {
        $this->mark($thread, true, FOSMessageEvents::POST_DELETE);
    }

    /**
     * {@inheritdoc}
     */
    public function markAsUndeleted(ThreadInterface $thread): void
    {
        $this->mark($thread, false, FOSMessageEvents::POST_UNDELETE);
    }

    /**
     * @param ThreadInterface $thread
     * @param bool $isDeleted
     * @param string $eventName
     *
     * @return void
     */
    private function mark(ThreadInterface $thread, bool $isDeleted, string $eventName): void
    {
        if (!$this->authorizer->canDeleteThread($thread)) {
            throw new AccessDeniedException('You are not allowed to delete this thread');
        }

        $thread->setIsDeletedByParticipant(
            $this->participantProvider->getAuthenticatedParticipant(),
            $isDeleted
        );

        $this->dispatcher->dispatch(new ThreadEvent($thread), $eventName);
    }
}
