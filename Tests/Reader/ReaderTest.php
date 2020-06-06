<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Reader;

use FOS\MessageBundle\Event\FOSMessageEvents;
use FOS\MessageBundle\Event\ReadableEvent;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\ModelManager\ReadableManagerInterface;
use FOS\MessageBundle\Reader\Reader;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Marks messages and threads as read or unread.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ReaderTest extends AbstractTestCase
{
    /**
     * The participantProvider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * The readable manager.
     *
     * @var ReadableManagerInterface
     */
    protected $readableManager;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var Reader
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);
        $this->readableManager     = \Mockery::mock(ReadableManagerInterface::class);
        $this->dispatcher          = \Mockery::mock(EventDispatcherInterface::class);

        $this->service = new Reader(
            $this->participantProvider,
            $this->readableManager,
            $this->dispatcher
        );
    }

    /**
     * @test
     */
    public function markAsRead(): void
    {
        $this->expectNotToPerformAssertions();

        $readable = \Mockery::mock(ReadableInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $readable->shouldReceive('isReadByParticipant')->once()->with($participant)->andReturnFalse();

        $this->readableManager->shouldReceive('markAsReadByParticipant')
            ->once()
            ->with($readable, $participant)
            ->andReturnNull();

        $expectedEvent = new ReadableEvent($readable);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(\Mockery::on(static function (ReadableEvent $event) use ($expectedEvent) {
                return $event->getReadable() === $expectedEvent->getReadable();
            }), FOSMessageEvents::POST_READ);

        $this->service->markAsRead($readable);
    }

    /**
     * @test
     */
    public function markAsReadIsReadByParticipantFalse(): void
    {
        $this->expectNotToPerformAssertions();

        $readable = \Mockery::mock(ReadableInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $readable->shouldReceive('isReadByParticipant')->once()->with($participant)->andReturnTrue();

        $this->readableManager->shouldReceive('markAsReadByParticipant')
            ->never();

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->never();

        $this->service->markAsRead($readable);
    }

    /**
     * @test
     */
    public function markAsUnread(): void
    {
        $this->expectNotToPerformAssertions();

        $readable = \Mockery::mock(ReadableInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $readable->shouldReceive('isReadByParticipant')->once()->with($participant)->andReturnTrue();

        $this->readableManager->shouldReceive('markAsUnreadByParticipant')
            ->once()
            ->with($readable, $participant)
            ->andReturnNull();

        $expectedEvent = new ReadableEvent($readable);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(\Mockery::on(static function (ReadableEvent $event) use ($expectedEvent) {
                return $event->getReadable() === $expectedEvent->getReadable();
            }), FOSMessageEvents::POST_UNREAD);

        $this->service->markAsUnread($readable);
    }

    /**
     * @test
     */
    public function markAsUnreadReadIsReadByParticipantTrue(): void
    {
        $this->expectNotToPerformAssertions();

        $readable = \Mockery::mock(ReadableInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $readable->shouldReceive('isReadByParticipant')->once()->with($participant)->andReturnFalse();

        $this->readableManager->shouldReceive('markAsUnreadByParticipant')
            ->never();

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->never();

        $this->service->markAsUnread($readable);
    }
}
