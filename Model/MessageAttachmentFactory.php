<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageAttachmentFactory
 */
class MessageAttachmentFactory implements MessageAttachmentFactoryInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * MessageFactory constructor.
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @return MessageAttachmentInterface
     */
    public function create(): MessageAttachmentInterface
    {
        return new $this->class();
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->class;
    }
}
