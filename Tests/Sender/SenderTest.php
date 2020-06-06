<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Sender;

use FOS\MessageBundle\Event\FOSMessageEvents;
use FOS\MessageBundle\Event\MessageEvent;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Sender\Sender;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Sends messages.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class SenderTest extends AbstractTestCase
{
    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;
    /**
     * @var Sender
     */
    private $service;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->messageManager = \Mockery::mock(MessageManagerInterface::class);
        $this->threadManager  = \Mockery::mock(ThreadManagerInterface::class);
        $this->dispatcher     = \Mockery::mock(EventDispatcherInterface::class);

        $this->service = new Sender(
            $this->messageManager,
            $this->threadManager,
            $this->dispatcher
        );
    }

    /**
     * @test
     */
    public function send(): void
    {
        $this->expectNotToPerformAssertions();

        $message = \Mockery::mock(MessageInterface::class);
        $thread = \Mockery::mock(ThreadInterface::class);
        $thread->shouldReceive('setIsDeleted')->once()->with(false);
            
        $message->shouldReceive('getThread')->times(4)->withNoArgs()->andReturn($thread);
        
        $this->threadManager->shouldReceive('saveThread')
            ->once()
            ->with($thread, false)
            ->andReturnNull();

        $this->messageManager->shouldReceive('saveMessage')
            ->once()
            ->with($message, false)
            ->andReturnNull();
        
        $this->messageManager->shouldReceive('saveMessage')
            ->once()
            ->with($message)
            ->andReturnNull();
        
        $expectedEvent = new MessageEvent($message);
        
        $f = static function (MessageEvent $event) use ($expectedEvent) {
            return $event->getMessage() === $expectedEvent->getMessage();
        };
        
        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with(\Mockery::on($f), FOSMessageEvents::POST_SEND);
        
        $this->service->send($message);
    }
}
