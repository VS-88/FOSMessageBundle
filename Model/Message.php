<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

/**
 * Abstract message model.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class Message implements MessageInterface
{
    /**
     * Unique id of the message.
     *
     * @var mixed
     */
    protected $id;

    /**
     * User who sent the message.
     *
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * Text body of the message.
     *
     * @var string
     */
    protected $body;

    /**
     * Date when the message was sent.
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Thread the message belongs to.
     *
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * Collection of MessageMetadata.
     *
     * @var Collection|MessageMetadata[]
     */
    protected $metadata;

    /**
     * @var Collection|MessageAttachmentInterface[]
     */
    protected $messageAttachments;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt          = new DateTime();
        $this->metadata           = new ArrayCollection();
        $this->messageAttachments = new ArrayCollection();
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
    public function getThread(): ThreadInterface
    {
        return $this->thread;
    }

    /**
     * {@inheritdoc}
     */
    public function setThread(ThreadInterface $thread): MessageInterface
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody(string $body): MessageInterface
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSender(): ParticipantInterface
    {
        return $this->sender;
    }

    /**
     * {@inheritdoc}
     */
    public function setSender(ParticipantInterface $sender): MessageInterface
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Gets the created at timestamp.
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->getCreatedAt()->getTimestamp();
    }

    /**
     * Adds MessageMetadata to the metadata collection.
     *
     * @param MessageMetadataInterface $meta
     * @return MessageInterface
     */
    public function addMetadata(MessageMetadataInterface $meta): MessageInterface
    {
        $this->metadata->add($meta);

        return $this;
    }

    /**
     * @param MessageAttachmentInterface $messageAttachment
     * @return MessageInterface
     */
    public function addMessageAttachment(MessageAttachmentInterface $messageAttachment): MessageInterface
    {
        $this->messageAttachments->add($messageAttachment);

        return $this;
    }

    /**
     * @param array $fileNames
     * @param MessageAttachmentFactoryInterface $messageAttachmentFactory
     *
     * @return MessageInterface
     */
    public function addMessageAttachments(
        array $fileNames,
        MessageAttachmentFactoryInterface $messageAttachmentFactory
    ): MessageInterface {
        foreach ($fileNames as $fileName) {
            $attachment = $messageAttachmentFactory->create();

            $attachment->setFileName($fileName);
            $attachment->setMessage($this);

            $this->addMessageAttachment($attachment);
        }

        return $this;
    }

    /**
     * @return Collection|MessageAttachmentInterface[]
     */
    public function getMessageAttachments(): Collection
    {
        return $this->messageAttachments;
    }

    /**
     * Get the MessageMetadata for a participant.
     *
     * @param ParticipantInterface $participant
     *
     * @return MessageMetadata
     */
    public function getMetadataForParticipant(ParticipantInterface $participant): ?MessageMetadata
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
    public function isReadByParticipant(ParticipantInterface $participant): bool
    {
        if ($meta = $this->getMetadataForParticipant($participant)) {
            return $meta->getIsRead();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsReadByParticipant(ParticipantInterface $participant, bool $isRead): void
    {
        if (!$meta = $this->getMetadataForParticipant($participant)) {
            throw new InvalidArgumentException(
                sprintf('No metadata exists for participant with id "%s"', $participant->getId())
            );
        }

        $meta->setIsRead($isRead);
    }
}
