<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Composer;

use FOS\MessageBundle\Composer\Composer;
use FOS\MessageBundle\MessageBuilder\NewThreadMessageBuilder;
use FOS\MessageBundle\MessageBuilder\ReplyMessageBuilder;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use PHPUnit\Framework\TestCase;

/**
 * Factory for message builders.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ComposerTest extends AbstractTestCase
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
     * @var Composer
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageManager = \Mockery::mock(MessageManagerInterface::class);
        $this->threadManager  = \Mockery::mock(ThreadManagerInterface::class);

        $this->service = new Composer(
            $this->messageManager,
            $this->threadManager
        );
    }

    /**
     * @test
     */
    public function newThread(): void
    {
        $thread = \Mockery::mock(
            ThreadInterface::class
        );

        $message = \Mockery::mock(
            MessageInterface::class
        );

        $message->shouldReceive('setThread')->once()->with($thread);
        $thread->shouldReceive('addMessage')->once()->with($message);

        $this->threadManager->shouldReceive('createThread')->once()->withNoArgs()->andReturn($thread);
        $this->messageManager->shouldReceive('createMessage')->once()->withNoArgs()->andReturn($message);

        self::assertInstanceOf(
            NewThreadMessageBuilder::class,
            $this->service->newThread()
        );
    }

    /**
     * @tes
     */
    public function reply(): void
    {
        $thread = \Mockery::mock(
            ThreadInterface::class
        );

        $message = \Mockery::mock(
            MessageInterface::class
        );

        $message->shouldReceive('setThread')->once()->with($thread);
        $thread->shouldReceive('addMessage')->once()->with($message);

        $this->messageManager->shouldReceive('createMessage')->once()->withNoArgs()->andReturn($message);

        self::assertInstanceOf(
            ReplyMessageBuilder::class,
            $this->service->reply($thread)
        );
    }
}
