<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Security;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\Authorizer;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Mockery;

/**
 * Manages permissions to manipulate threads and messages.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class AuthorizerTest extends AbstractTestCase
{
    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * @var Authorizer
     */
    private $service;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->participantProvider = Mockery::mock(ParticipantProviderInterface::class);

        $this->service = new Authorizer($this->participantProvider);
    }

    /**
     * @test
     */
    public function canSeeThread(): void
    {
        $thread      = Mockery::mock(ThreadInterface::class);
        $participant = Mockery::mock(ParticipantInterface::class);

        $participant->shouldReceive('isAdmin')->once()->andReturnFalse();

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->twice()
            ->withNoArgs()
            ->andReturn($participant);

        $thread->shouldReceive('isParticipant')->twice()->with($participant)->andReturnTrue();

        self::assertTrue($this->service->canSeeThread($thread));

        $participant->shouldReceive('isAdmin')->once()->andReturnFalse();

        self::assertTrue($this->service->canDeleteThread($thread));
    }

    /**
     * @test
     */
    public function canMessageParticipant(): void
    {
        $participant = Mockery::mock(ParticipantInterface::class);

        self::assertTrue($this->service->canMessageParticipant($participant));
    }
}
