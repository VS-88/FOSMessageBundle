<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageFactory
 */
class MessageMetadataFactory implements MessageMetadataFactoryInterface
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
     * @return MessageMetadataInterface
     */
    public function create(): MessageMetadataInterface
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
