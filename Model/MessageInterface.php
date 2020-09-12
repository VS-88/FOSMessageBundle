<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

use DateTime;
use Doctrine\Common\Collections\Collection;

/**
 * Message model.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface MessageInterface extends ReadableInterface
{
    /**
     * Gets the message unique id.
     *
     * @return mixed
     */
    public function getId();

    /**
     * @return ThreadInterface
     */
    public function getThread(): ThreadInterface;

    /**
     * @param  ThreadInterface
     */
    public function setThread(ThreadInterface $thread): self;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return string
     */
    public function getBody(): string;

    /**
     * @param  string
     */
    public function setBody(string $body): self;

    /**
     * @return ParticipantInterface
     */
    public function getSender(): ParticipantInterface;

    /**
     * @param  ParticipantInterface
     */
    public function setSender(ParticipantInterface $sender): self;

    /**
     * @param MessageAttachmentInterface $messageAttachment
     * @return MessageInterface
     */
    public function addMessageAttachment(MessageAttachmentInterface $messageAttachment): MessageInterface;

    /**
     * @param array $fileNames
     * @param MessageAttachmentFactoryInterface $messageAttachmentFactory
     *
     * @return MessageInterface
     */
    public function addMessageAttachments(
        array $fileNames,
        MessageAttachmentFactoryInterface $messageAttachmentFactory
    ): MessageInterface;

    /**
     * @return Collection|MessageAttachmentInterface[]
     */
    public function getMessageAttachments(): Collection;
}
