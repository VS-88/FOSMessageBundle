<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

/**
 * Class ThreadMetadataFactory
 */
class ThreadMetadataFactory implements ThreadMetadataFactoryInterface
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
     * @return ThreadMetadataInterface
     */
    public function create(): ThreadMetadataInterface
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
