<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Event;

use FOS\MessageBundle\Model\MessageInterface;

/**
 * Class MessageEvent
 * @package FOS\MessageBundle\Event
 */
class MessageEvent extends ThreadEvent
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
        parent::__construct($message->getThread());

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
