<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\MessageBuilder;

use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;

/**
 * Fluent interface message builder.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class AbstractMessageBuilder
{
    /**
     * The message we are building.
     *
     * @var MessageInterface
     */
    protected $message;

    /**
     * The thread the message goes in.
     *
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * AbstractMessageBuilder constructor.
     * @param MessageInterface $message
     * @param ThreadInterface $thread
     */
    public function __construct(MessageInterface $message, ThreadInterface $thread)
    {
        $this->message = $message;
        $this->thread = $thread;

        $this->message->setThread($thread);
        $thread->addMessage($message);
    }

    /**
     * Gets the created message.
     *
     * @return MessageInterface the message created
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    /**
     * @param  string
     *
     * @return AbstractMessageBuilder (fluent interface)
     */
    public function setBody(string $body): AbstractMessageBuilder
    {
        $this->message->setBody($body);

        return $this;
    }

    /**
     * @param ParticipantInterface $sender
     *
     * @return AbstractMessageBuilder (fluent interface)
     */
    public function setSender(ParticipantInterface $sender): AbstractMessageBuilder
    {
        $this->message->setSender($sender);
        $this->thread->addParticipant($sender);

        return $this;
    }
}
