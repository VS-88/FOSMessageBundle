<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Security;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\ParticipantProvider;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Provides the authenticated participant.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ParticipantProviderTest extends AbstractTestCase
{
    /**
     * @var TokenStorageInterface
     */
    protected $securityContext;

    /**
     * @var ParticipantProvider
     */
    private $service;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->securityContext = \Mockery::mock(TokenStorageInterface::class);

        $this->service = new ParticipantProvider($this->securityContext);
    }

    /**
     * @test
     */
    public function getAuthenticatedParticipant(): void
    {
        $token = \Mockery::mock(TokenInterface::class);

        $user = \Mockery::mock(ParticipantInterface::class);

        $this->securityContext->shouldReceive('getToken')->once()->withNoArgs()->andReturn($token);

        $token->shouldReceive('getUser')->once()->withNoArgs()->andReturn($user);

        self::assertSame($user, $this->service->getAuthenticatedParticipant());
    }

    /**
     * @test
     */
    public function getAuthenticatedParticipantExceptionCase(): void
    {
        $this->expectException(AccessDeniedException::class);

        $token = \Mockery::mock(TokenInterface::class);

        $user = new \stdClass();

        $this->securityContext->shouldReceive('getToken')->once()->withNoArgs()->andReturn($token);

        $token->shouldReceive('getUser')->once()->withNoArgs()->andReturn($user);

        $this->service->getAuthenticatedParticipant();
    }
}
