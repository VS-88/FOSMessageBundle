<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Provider;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\Provider;
use FOS\MessageBundle\Reader\ReaderInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Provides threads for the current authenticated user.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ProviderTest extends AbstractTestCase
{
    /**
     * The thread manager.
     *
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * The message manager.
     *
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * The reader used to mark threads as read.
     *
     * @var ReaderInterface
     */
    protected $threadReader;

    /**
     * The authorizer manager.
     *
     * @var authorizerInterface
     */
    protected $authorizer;

    /**
     * The participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;
    /**
     * @var Provider
     */
    private $service;

    protected function setUp(): void
    {
        $this->threadManager = \Mockery::mock(ThreadManagerInterface::class);
        $this->messageManager = \Mockery::mock(MessageManagerInterface::class);
        $this->threadReader = \Mockery::mock(ReaderInterface::class);
        $this->authorizer = \Mockery::mock(AuthorizerInterface::class);
        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);
        
        $this->service = new Provider(
            $this->threadManager,
            $this->messageManager,
            $this->threadReader,
            $this->authorizer,
            $this->participantProvider
        );
    }

    /**
     * @test
     */
    public function getInboxThreads(): void
    {
        $thread = \Mockery::mock(ThreadInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);
        $expected = [$thread];

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->withNoArgs()
            ->andReturn($participant);

        $this->threadManager->shouldReceive('findParticipantInboxThreads')
            ->once()
            ->with($participant)
            ->andReturn($expected);

        $actual = $this->service->getInboxThreads($participant);

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function getSentThreads(): void
    {
        $thread = \Mockery::mock(ThreadInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);
        $expected = [$thread];

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->withNoArgs()
            ->andReturn($participant);

        $this->threadManager->shouldReceive('findParticipantSentThreads')
            ->once()
            ->with($participant)
            ->andReturn($expected);

        $actual = $this->service->getSentThreads($participant);

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function getDeletedThreads(): void
    {
        $thread = \Mockery::mock(ThreadInterface::class);

        $participant = \Mockery::mock(ParticipantInterface::class);
        $expected = [$thread];

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->withNoArgs()
            ->andReturn($participant);

        $this->threadManager->shouldReceive('findParticipantDeletedThreads')
            ->once()
            ->with($participant)
            ->andReturn($expected);

        $actual = $this->service->getDeletedThreads($participant);

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function getThread(): void
    {
        $threadId = 7;

        $thread = \Mockery::mock(ThreadInterface::class);
        $this->threadManager->shouldReceive('findThreadById')
            ->once()
            ->with($threadId)
            ->andReturn($thread);

        $this->authorizer->shouldReceive('canSeeThread')->once()->with($thread)->andReturnTrue();

        $thread->shouldReceive('getMessages')->once();

        $this->threadReader->shouldReceive('markAsRead')->once()->with($thread);

        $actual = $this->service->getThread($threadId);

        self::assertSame($thread, $actual);
    }

    /**
     * @test
     */
    public function getThreadNotFoundCase(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $threadId = 7;

        $this->threadManager->shouldReceive('findThreadById')
            ->once()
            ->with($threadId)
            ->andReturnNull();

        $this->service->getThread($threadId);
    }

    /**
     * @test
     */
    public function getThreadAccessDenied(): void
    {
        $this->expectException(AccessDeniedException::class);

        $threadId = 7;

        $thread = \Mockery::mock(ThreadInterface::class);
        $this->threadManager->shouldReceive('findThreadById')
            ->once()
            ->with($threadId)
            ->andReturn($thread);

        $this->authorizer->shouldReceive('canSeeThread')->once()->with($thread)->andReturnFalse();

        $this->service->getThread($threadId);
    }


    /**
     * @test
     */
    public function getNbUnreadMessages(): void
    {
        $participant = \Mockery::mock(ParticipantInterface::class);
        $expected = 777;

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->withNoArgs()
            ->andReturn($participant);

        $this->messageManager->shouldReceive('getNbUnreadMessageByParticipant')
            ->once()
            ->with($participant)
            ->andReturn($expected);

        $actual = $this->service->getNbUnreadMessages($participant);

        self::assertSame($expected, $actual);
    }
}
