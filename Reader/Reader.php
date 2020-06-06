<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Reader;

use FOS\MessageBundle\Event\FOSMessageEvents;
use FOS\MessageBundle\Event\ReadableEvent;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\ModelManager\ReadableManagerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Marks messages and threads as read or unread.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Reader implements ReaderInterface
{
    /**
     * The participantProvider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * The readable manager.
     *
     * @var ReadableManagerInterface
     */
    protected $readableManager;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Reader constructor.
     *
     * @param ParticipantProviderInterface $participantProvider
     * @param ReadableManagerInterface $readableManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        ParticipantProviderInterface $participantProvider,
        ReadableManagerInterface $readableManager,
        EventDispatcherInterface $dispatcher
    ) {
        $this->participantProvider = $participantProvider;
        $this->readableManager = $readableManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function markAsRead(ReadableInterface $readable): void
    {
        $participant = $this->participantProvider->getAuthenticatedParticipant();
        if ($readable->isReadByParticipant($participant)) {
            return;
        }
        $this->readableManager->markAsReadByParticipant($readable, $participant);

        $this->dispatcher->dispatch(new ReadableEvent($readable), FOSMessageEvents::POST_READ);
    }

    /**
     * {@inheritdoc}
     */
    public function markAsUnread(ReadableInterface $readable): void
    {
        $participant = $this->participantProvider->getAuthenticatedParticipant();
        if (!$readable->isReadByParticipant($participant)) {
            return;
        }
        $this->readableManager->markAsUnreadByParticipant($readable, $participant);

        $this->dispatcher->dispatch(new ReadableEvent($readable), FOSMessageEvents::POST_UNREAD);
    }
}
