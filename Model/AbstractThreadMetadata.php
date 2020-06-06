<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

use DateTime;

/**
 * Class ThreadMetadata
 */
abstract class AbstractThreadMetadata implements ThreadMetadataInterface
{
    /**
     * @var int|string
     */
    protected $id;

    /**
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * @var bool
     */
    protected $isDeleted = false;

    /**
     * Date of last message written by the participant.
     *
     * @var DateTime
     */
    protected $lastParticipantMessageDate;

    /**
     * Date of last message written by another participant.
     *
     * @var DateTime
     */
    protected $lastMessageDate;

    /**
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * @return ParticipantInterface
     */
    public function getParticipant(): ParticipantInterface
    {
        return $this->participant;
    }

    /**
     * @param ParticipantInterface $participant
     * @return ThreadMetadataInterface
     */
    public function setParticipant(ParticipantInterface $participant): ThreadMetadataInterface
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     * @return ThreadMetadataInterface
     */
    public function setIsDeleted(bool $isDeleted): ThreadMetadataInterface
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastParticipantMessageDate(): DateTime
    {
        return $this->lastParticipantMessageDate;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setLastParticipantMessageDate(DateTime $date): ThreadMetadataInterface
    {
        $this->lastParticipantMessageDate = $date;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastMessageDate(): DateTime
    {
        return $this->lastMessageDate;
    }

    /**
     * @param DateTime $date
     *
     * @return $this
     */
    public function setLastMessageDate(DateTime $date): ThreadMetadataInterface
    {
        $this->lastMessageDate = $date;

        return $this;
    }

    /**
     * Gets the thread map id.
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ThreadInterface
     */
    public function getThread(): ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @param ThreadInterface $thread
     *
     * @return ThreadMetadataInterface
     */
    public function setThread(ThreadInterface $thread): ThreadMetadataInterface
    {
        $this->thread = $thread;

        return $this;
    }
}
