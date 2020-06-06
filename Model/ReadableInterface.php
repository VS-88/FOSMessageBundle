<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

/**
 * Interface ReadableInterface
 * @package FOS\MessageBundle\Model
 */
interface ReadableInterface
{
    /**
     * Tells if this is read by this participant.
     *
     * @param ParticipantInterface $participant
     * @return bool
     */
    public function isReadByParticipant(ParticipantInterface $participant): bool;

    /**
     * Sets whether or not this participant has read this.
     *
     * @param ParticipantInterface $participant
     * @param bool                 $isRead
     */
    public function setIsReadByParticipant(ParticipantInterface $participant, bool $isRead): void;
}
