<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Security;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;

/**
 * Manages permissions to manipulate threads and messages.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Authorizer implements AuthorizerInterface
{
    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * Authorizer constructor.
     * @param ParticipantProviderInterface $participantProvider
     */
    public function __construct(ParticipantProviderInterface $participantProvider)
    {
        $this->participantProvider = $participantProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function canSeeThread(ThreadInterface $thread): bool
    {
        $participant = $this->participantProvider->getAuthenticatedParticipant();
        return $thread->isParticipant($participant);
    }

    /**
     * {@inheritdoc}
     */
    public function canDeleteThread(ThreadInterface $thread): bool
    {
        return $this->canSeeThread($thread);
    }

    /**
     * {@inheritdoc}
     */
    public function canMessageParticipant(ParticipantInterface $participant): bool
    {
        return true;
    }
}
