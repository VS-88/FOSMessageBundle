<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Composer;

use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;
use FOS\MessageBundle\MessageBuilder\NewThreadMessageBuilder;
use FOS\MessageBundle\MessageBuilder\ReplyMessageBuilder;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;

/**
 * Factory for message builders.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Composer implements ComposerInterface
{
    /**
     * Message manager.
     *
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * Thread manager.
     *
     * @var ThreadManagerInterface
     */
    private $threadManager;

    /**
     * Composer constructor.
     * @param MessageManagerInterface $messageManager
     * @param ThreadManagerInterface $threadManager
     */
    public function __construct(MessageManagerInterface $messageManager, ThreadManagerInterface $threadManager)
    {
        $this->messageManager = $messageManager;
        $this->threadManager = $threadManager;
    }

    /**
     * {@inheritDoc}
     */
    public function newThread(): AbstractMessageBuilder
    {
        $thread = $this->threadManager->createThread();
        $message = $this->messageManager->createMessage();

        return new NewThreadMessageBuilder($message, $thread);
    }

    /**
     * {@inheritDoc}
     */
    public function reply(ThreadInterface $thread): AbstractMessageBuilder
    {
        $message = $this->messageManager->createMessage();

        return new ReplyMessageBuilder($message, $thread);
    }
}
