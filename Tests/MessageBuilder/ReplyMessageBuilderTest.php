<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\MessageBuilder;

use FOS\MessageBundle\MessageBuilder\ReplyMessageBuilder;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;

/**
 * Fluent interface message builder for reply to a thread.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ReplyMessageBuilderTest extends AbstractTestCase
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
     * @var ReplyMessageBuilder
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->message = \Mockery::mock(MessageInterface::class);
        $this->thread  = \Mockery::mock(ThreadInterface::class);

        $this->message->shouldReceive('setThread')->once()->with($this->thread);
        $this->thread->shouldReceive('addMessage')->once()->with($this->message);

        $this->service = new ReplyMessageBuilder(
            $this->message,
            $this->thread
        );
    }

    /**
     * Gets the created message.
     *
     * @test
     */
    public function getMessage(): void
    {
        self::assertSame($this->message, $this->service->getMessage());
    }

    /**
     * @test
     */
    public function setBody(): void
    {
        $body = 'some body';

        $this->message->shouldReceive('setBody')->once()->with($body);
        $res = $this->service->setBody($body);

        self::assertSame($res, $this->service);
    }

    /**
     * @test
     */
    public function setSender(): void
    {
        $sender = \Mockery::mock(ParticipantInterface::class);

        $this->message->shouldReceive('setSender')->once()->with($sender);
        $this->thread->shouldReceive('addParticipant')->once()->with($sender);

        $res = $this->service->setSender($sender);

        self::assertSame($res, $this->service);
    }
}
