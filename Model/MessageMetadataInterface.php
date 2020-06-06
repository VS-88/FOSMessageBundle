<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageMetadata
 */
interface MessageMetadataInterface
{
    /**
     * @return ParticipantInterface
     */
    public function getParticipant(): ParticipantInterface;

    /**
     * @param ParticipantInterface $participant
     *
     * @return MessageMetadata
     */
    public function setParticipant(ParticipantInterface $participant): MessageMetadata;

    /**
     * @return bool
     */
    public function getIsRead(): bool;

    /**
     * @param bool $isRead
     *
     * @return MessageMetadata
     */
    public function setIsRead(bool $isRead): MessageMetadata;
}
