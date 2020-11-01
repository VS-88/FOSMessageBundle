<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

/**
 * Abstract thread model.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class Thread implements ThreadInterface
{
    /**
     * Unique id of the thread.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Text subject of the thread.
     *
     * @var string
     */
    protected $subject;

    /**
     * Tells if the thread is spam or flood.
     *
     * @var bool
     */
    protected $isSpam = false;

    /**
     * Messages contained in this thread.
     *
     * @var Collection|MessageInterface[]
     */
    protected $messages;

    /**
     * Thread metadata.
     *
     * @var Collection|AbstractThreadMetadata[]
     */
    protected $metadata;

    /**
     * Users participating in this conversation.
     *
     * @var Collection|ParticipantInterface[]
     */
    protected $participants;

    /**
     * Date this thread was created at.
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Participant that created the thread.
     *
     * @var ParticipantInterface
     */
    protected $createdBy;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->metadata = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(DateTime $createdAt): ThreadInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedBy(): ?ParticipantInterface
    {
        return $this->createdBy;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedBy(ParticipantInterface $participant): ThreadInterface
    {
        $this->createdBy = $participant;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject(string $subject): ThreadInterface
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSpam(): bool
    {
        return $this->isSpam;
    }

    /**
     * @param bool
     *
     * @return Thread
     */
    public function setIsSpam($isSpam): ThreadInterface
    {
        $this->isSpam = (bool) $isSpam;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMessage(MessageInterface $message): ThreadInterface
    {
        $this->messages->add($message);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstMessage(): ?MessageInterface
    {
        return $this->getMessages()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastMessage(): ?MessageInterface
    {
        return $this->getMessages()->last();
    }

    /**
     * {@inheritdoc}
     */
    public function isDeletedByParticipant(ParticipantInterface $participant): bool
    {
        if ($meta = $this->getMetadataForParticipant($participant)) {
            return $meta->getIsDeleted();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsDeletedByParticipant(ParticipantInterface $participant, bool $isDeleted): ThreadInterface
    {
        if (!$meta = $this->getMetadataForParticipant($participant)) {
            throw new InvalidArgumentException(
                sprintf(
                    'No metadata exists for participant with id "%s"',
                    $participant->getId()
                )
            );
        }

        $meta->setIsDeleted($isDeleted);

        if ($isDeleted) {
            // also mark all thread messages as read
            foreach ($this->getMessages() as $message) {
                $message->setIsReadByParticipant($participant, true);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsDeleted(bool $isDeleted): ThreadInterface
    {
        foreach ($this->getParticipants() as $participant) {
            $this->setIsDeletedByParticipant($participant, $isDeleted);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadByParticipant(ParticipantInterface $participant): bool
    {
        foreach ($this->getMessages() as $message) {
            if (!$message->isReadByParticipant($participant)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsReadByParticipant(ParticipantInterface $participant, bool $isRead): void
    {
        foreach ($this->getMessages() as $message) {
            $message->setIsReadByParticipant($participant, $isRead);
        }
    }

    /**
     * Adds ThreadMetadata to the metadata collection.
     *
     * @param ThreadMetadataInterface $meta
     * @return ThreadInterface
     */
    public function addMetadata(ThreadMetadataInterface $meta): ThreadInterface
    {
        $meta->setThread($this);
        $this->metadata->add($meta);

        return $this;
    }

    /**
     * Gets the ThreadMetadata for a participant.
     *
     * @param ParticipantInterface $participant
     *
     * @return AbstractThreadMetadata
     */
    public function getMetadataForParticipant(ParticipantInterface $participant): ?AbstractThreadMetadata
    {
        foreach ($this->metadata as $meta) {
            if ($meta->getParticipant()->getId() === $participant->getId()) {
                return $meta;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getOtherParticipants(ParticipantInterface $participant): array
    {
        $otherParticipants = $this->getParticipants()->toArray();

        /**
         * @var int|string $key
         */
        $key = array_search($participant, $otherParticipants, true);

        if ($key !== false) {
            unset($otherParticipants[$key]);
        }

        // we want to reset the array indexes
        return array_values($otherParticipants);
    }


    /**
     * {@inheritdoc}
     */
    public function isParticipant(ParticipantInterface $participant): bool
    {
        return $this->getParticipantsCollection()->contains($participant);
    }

    /**
     * Get the collection of ModelThreadMetadata.
     *
     * @return Collection
     */
    public function getAllMetadata(): Collection
    {
        return $this->metadata;
    }


    /**
     * {@inheritdoc}
     */
    public function getParticipants(): Collection
    {
        return $this->getParticipantsCollection();
    }

    /**
     * Gets the users participating in this conversation.
     *
     * Since the ORM schema does not map the participants collection field, it
     * must be created on demand.
     *
     * @return ArrayCollection|ParticipantInterface[]
     */
    protected function getParticipantsCollection(): Collection
    {
        if ($this->participants === null) {
            $this->participants = new ArrayCollection();

            foreach ($this->metadata as $data) {
                $this->participants->add($data->getParticipant());
            }
        }

        return $this->participants;
    }

    /**
     * {@inheritdoc}
     */
    public function addParticipant(ParticipantInterface $participant): ThreadInterface
    {
        if (!$this->isParticipant($participant)) {
            $this->getParticipantsCollection()->add($participant);
        }

        return  $this;
    }

    /**
     * Adds many participants to the thread.
     *
     * @param iterable
     *
     * @return ThreadInterface
     *
     * @throws InvalidArgumentException
     */
    public function addParticipants(iterable $participants): ThreadInterface
    {
        foreach ($participants as $participant) {
            $this->addParticipant($participant);
        }

        return $this;
    }
}
