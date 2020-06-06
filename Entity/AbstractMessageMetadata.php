<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\MessageMetadata as BaseMessageMetadata;

/**
 * Class AbstractMessageMetadata
 * @package FOS\MessageBundle\Entity
 */
abstract class AbstractMessageMetadata extends BaseMessageMetadata
{
    /**
     * @var int|string
     */
    protected $id;

    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @ORM\Column(name="is_read", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $isRead = false;

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     * @return AbstractMessageMetadata
     */
    public function setMessage(MessageInterface $message): AbstractMessageMetadata
    {
        $this->message = $message;

        return $this;
    }
}
