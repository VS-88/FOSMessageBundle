<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageAttachment
 */
abstract class MessageAttachment implements MessageAttachmentInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     * @return MessageAttachment
     */
    public function setMessage(MessageInterface $message): MessageAttachmentInterface
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return MessageAttachment
     */
    public function setFileName(string $fileName): MessageAttachmentInterface
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
