<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageAttachment
 */
interface MessageAttachmentInterface
{
    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface;

    /**
     * @param MessageInterface $message
     * @return MessageAttachment
     */
    public function setMessage(MessageInterface $message): MessageAttachmentInterface;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @param string $fileName
     * @return MessageAttachment
     */
    public function setFileName(string $fileName): MessageAttachmentInterface;

    /**
     * @return int
     */
    public function getId(): int;
}
