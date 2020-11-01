<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageMetadata
 */
abstract class MessageMetadata implements MessageMetadataInterface
{
    /**
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * @var bool
     */
    protected $isRead = false;

    /**
     * @return ParticipantInterface
     */
    public function getParticipant(): ParticipantInterface
    {
        return $this->participant;
    }

    /**
     * @param ParticipantInterface $participant
     *
     * @return MessageMetadata
     */
    public function setParticipant(ParticipantInterface $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsRead(): bool
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     *
     * @return MessageMetadata
     */
    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }


    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function setMessage(MessageInterface $message): MessageMetadataInterface
    {
        $this->message = $message;

        return $this;
    }
}
