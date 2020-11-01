<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

use DateTime;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

/**
 * Interface ThreadInterface
 * @package FOS\MessageBundle\Model
 */
interface ThreadInterface extends ReadableInterface
{
    /**
     * Gets the message unique id.
     *
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @param string
     *
     * @return ThreadInterface
     */
    public function setSubject(string $subject): self;

    /**
     * Gets the messages contained in the thread.
     *
     * @return MessageInterface[]|Collection
     */
    public function getMessages(): Collection;

    /**
     * Adds a new message to the thread.
     *
     * @param MessageInterface $message
     * @return ThreadInterface
     */
    public function addMessage(MessageInterface $message): self;

    /**
     * Gets the first message of the thread.
     *
     * @return MessageInterface
     */
    public function getFirstMessage(): ?MessageInterface;

    /**
     * Gets the last message of the thread.
     *
     * @return MessageInterface
     */
    public function getLastMessage(): ?MessageInterface;

    /**
     * Gets the participant that created the thread
     * Generally the sender of the first message.
     *
     * @return ParticipantInterface
     */
    public function getCreatedBy(): ?ParticipantInterface;

    /**
     * Sets the participant that created the thread
     * Generally the sender of the first message.
     * @param ParticipantInterface $participant
     * @return ThreadInterface
     */
    public function setCreatedBy(ParticipantInterface $participant): self;

    /**
     * Gets the date this thread was created at
     * Generally the date of the first message.
     *
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime;

    /**
     * Sets the date this thread was created at
     * Generally the date of the first message.
     * @param DateTime $createdAt
     * @return ThreadInterface
     */
    public function setCreatedAt(DateTime $createdAt): self;

    /**
     * Gets the users participating in this conversation.
     *
     * @return ParticipantInterface[]|Collection
     */
    public function getParticipants(): Collection;

    /**
     * Tells if the user participates to the conversation.
     *
     * @param ParticipantInterface $participant
     *
     * @return bool
     */
    public function isParticipant(ParticipantInterface $participant): bool;

    /**
     * Adds a participant to the thread
     * If it already exists, nothing is done.
     * @param ParticipantInterface $participant
     * @return ThreadInterface
     */
    public function addParticipant(ParticipantInterface $participant): self;

    /**
     * Tells if this thread is deleted by this participant.
     *
     * @param ParticipantInterface $participant
     * @return bool
     */
    public function isDeletedByParticipant(ParticipantInterface $participant): bool;

    /**
     * Sets whether or not this participant has deleted this thread.
     *
     * @param ParticipantInterface $participant
     * @param bool $isDeleted
     * @return ThreadInterface
     */
    public function setIsDeletedByParticipant(ParticipantInterface $participant, bool $isDeleted): self;

    /**
     * Sets the thread as deleted or not deleted for all participants.
     *
     * @param bool $isDeleted
     * @return ThreadInterface
     */
    public function setIsDeleted(bool $isDeleted): self;

    /**
     * Get the participants this participant is talking with.
     *
     * @param ParticipantInterface $participant
     *
     * @return ParticipantInterface[]
     */
    public function getOtherParticipants(ParticipantInterface $participant): array;

    /**
     * Adds ThreadMetadata to the metadata collection.
     *
     * @param ThreadMetadataInterface $meta
     * @return ThreadInterface
     */
    public function addMetadata(ThreadMetadataInterface $meta): ThreadInterface;

    /**
     * Get the collection of ModelThreadMetadata.
     *
     * @return Collection
     */
    public function getAllMetadata(): Collection;

    /**
     * @return bool
     */
    public function getIsSpam(): bool;

    /**
     * @param bool
     *
     * @return Thread
     */
    public function setIsSpam($isSpam): self;

    /**
     * Adds many participants to the thread.
     *
     * @param iterable
     *
     * @return ThreadInterface
     *
     * @throws InvalidArgumentException
     */
    public function addParticipants(iterable $participants): ThreadInterface;

    /**
     * Gets the ThreadMetadata for a participant.
     *
     * @param ParticipantInterface $participant
     *
     * @return AbstractThreadMetadata
     */
    public function getMetadataForParticipant(ParticipantInterface $participant): ?AbstractThreadMetadata;
}
