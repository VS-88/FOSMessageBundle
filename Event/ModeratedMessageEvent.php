<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Event;

use FOS\MessageBundle\Model\MessageInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class MessageEvent
 * @package FOS\MessageBundle\Event
 */
class ModeratedMessageEvent extends Event
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * MessageEvent constructor.
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }
}
