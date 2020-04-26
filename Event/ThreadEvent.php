<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Event;

use FOS\MessageBundle\Model\ThreadInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ThreadEvent extends Event
{
    /**
     * @var ThreadInterface
     */
    private $thread;

    public function __construct(ThreadInterface $thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return ThreadInterface
     */
    public function getThread(): ThreadInterface
    {
        return $this->thread;
    }
}
