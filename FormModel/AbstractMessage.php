<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormModel;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AbstractMessage
 * @package FOS\MessageBundle\FormModel
 */
abstract class AbstractMessage
{
    /**
     * @var string
     */
    protected $body;

    /**
     * @var array|UploadedFile[]
     */
    protected $attachments = [];

    /**
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * @return UploadedFile[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param UploadedFile[] $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }
}
