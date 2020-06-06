<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageFactory
 */
class MessageFactory implements MessageFactoryInterface
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
     * @return MessageInterface
     */
    public function create(): MessageInterface
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
