<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Model\Message as BaseMessage;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\MessageMetadataInterface;

/**
 * Class Message
 */
abstract class AbstractMessage extends BaseMessage
{
    /**
     * @ORM\Column(name="body", type="text", nullable=false)
     *
     * @var string
     */
    protected $body;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Get the collection of MessageMetadata.
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
    public function addMetadata(MessageMetadataInterface $meta): MessageInterface
    {
        $meta->setMessage($this);
        parent::addMetadata($meta);

        return $this;
    }
}
