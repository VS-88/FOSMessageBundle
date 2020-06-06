<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Deleter;

use FOS\MessageBundle\Deleter\Deleter;
use FOS\MessageBundle\Event\FOSMessageEvents;
use FOS\MessageBundle\Event\ThreadEvent;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Marks threads as deleted.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class DeleterTest extends AbstractTestCase
{
    /**
     * The authorizer instance.
     *
     * @var AuthorizerInterface
     */
    private $authorizer;

    /**
     * The participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    private $participantProvider;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var Deleter
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizer = \Mockery::mock(AuthorizerInterface::class);
        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);
        $this->dispatcher = \Mockery::mock(EventDispatcherInterface::class);

        $this->service = new Deleter(
            $this->authorizer,
            $this->participantProvider,
            $this->dispatcher
        );
    }

    /**
     * @test
     */
    public function markAsDeleted(): void
    {
        $thread = \Mockery::mock(ThreadInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $thread->shouldReceive('setIsDeletedByParticipant')
            ->once()
            ->with($participant, true);

        $this->authorizer->shouldReceive('canDeleteThread')->once($thread)->andReturnTrue();

        $expectedEvent = new ThreadEvent($thread);

        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with(
                \Mockery::on(static function (ThreadEvent $event) use ($expectedEvent) {
                    return $event->getThread() === $expectedEvent->getThread();
                }),
                true
            );

        self::assertNull($this->service->markAsDeleted($thread));
    }

    /**
     * @test
     */
    public function markAsUnDeleted(): void
    {
        $thread = \Mockery::mock(ThreadInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $thread->shouldReceive('setIsDeletedByParticipant')
            ->once()
            ->with($participant, false);

        $this->authorizer->shouldReceive('canDeleteThread')->once($thread)->andReturnTrue();

        $expectedEvent = new ThreadEvent($thread);

        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with(
                \Mockery::on(static function (ThreadEvent $event) use ($expectedEvent) {
                    return $event->getThread() === $expectedEvent->getThread();
                }),
                true
            );

        self::assertNull($this->service->markAsUnDeleted($thread));
    }

    /**
     * @test
     */
    public function markAsDeletedNotAllowedCase(): void
    {
        $this->expectException(AccessDeniedException::class);

        $thread = \Mockery::mock(ThreadInterface::class);

        $this->authorizer->shouldReceive('canDeleteThread')->once($thread)->andReturnFalse();

        $this->service->markAsDeleted($thread);
    }
}
