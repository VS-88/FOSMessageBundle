<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

/**
 * Class ThreadFactory
 */
class ThreadFactory implements ThreadFactoryInterface
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
     * @return ThreadInterface
     */
    public function create(): ThreadInterface
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
