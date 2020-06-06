<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Event;

use FOS\MessageBundle\Model\ReadableInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ReadableEvent
 * @package FOS\MessageBundle\Event
 */
class ReadableEvent extends Event
{
    /**
     * @var ReadableInterface
     */
    private $readable;

    /**
     * ReadableEvent constructor.
     * @param ReadableInterface $readable
     */
    public function __construct(ReadableInterface $readable)
    {
        $this->readable = $readable;
    }

    /**
     * @return ReadableInterface
     */
    public function getReadable(): ReadableInterface
    {
        return $this->readable;
    }
}
