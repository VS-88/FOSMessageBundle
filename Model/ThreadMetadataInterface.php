<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

use DateTime;

/**
 * Class ThreadMetadata
 */
interface ThreadMetadataInterface
{
    /**
     * @return ParticipantInterface
     */
    public function getParticipant(): ParticipantInterface;

    /**
     * @param ParticipantInterface $participant
     * @return self
     */
    public function setParticipant(ParticipantInterface $participant): self;

    /**
     * @return bool
     */
    public function getIsDeleted(): bool;

    /**
     * @param bool $isDeleted
     * @return self
     */
    public function setIsDeleted(bool $isDeleted): self;

    /**
     * @return DateTime
     */
    public function getLastParticipantMessageDate(): DateTime;

    /**
     * @param DateTime $date
     * @return self
     */
    public function setLastParticipantMessageDate(DateTime $date): self;

    /**
     * @return DateTime
     */
    public function getLastMessageDate(): DateTime;

    /**
     * @param DateTime $date
     *
     * @return $this
     */
    public function setLastMessageDate(DateTime $date): self;

    /**
     * Gets the thread map id.
     *
     * @return int|string
     */
    public function getId();

    /**
     * @return ThreadInterface
     */
    public function getThread(): ThreadInterface;

    /**
     * @param ThreadInterface $thread
     *
     * @return self
     */
    public function setThread(ThreadInterface $thread): self;
}
