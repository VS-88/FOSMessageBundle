<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Deleter;

use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
        $this->mark($thread, true);
    }

    /**
     * {@inheritdoc}
     */
    public function markAsUndeleted(ThreadInterface $thread): void
    {
        $this->mark($thread, false);
    }

    /**
     * @param ThreadInterface $thread
     * @param bool $isDeleted
     *
     * @return void
     */
    private function mark(ThreadInterface $thread, bool $isDeleted): void
    {
        $p = $this->participantProvider->getAuthenticatedParticipant();

        $thread->setIsDeletedByParticipant(
            $p,
            $isDeleted
        );
    }
}
