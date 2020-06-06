<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Validator;

use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Validator\ReplyAuthorization;
use FOS\MessageBundle\Validator\ReplyAuthorizationValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class ReplyAuthorizationValidator
 * @package FOS\MessageBundle\Validator
 */
class ReplyAuthorizationValidatorTest extends AbstractTestCase
{
    /**
     * @var AuthorizerInterface
     */
    protected $authorizer;

    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;
    /**
     * @var ReplyAuthorizationValidator
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizer          = \Mockery::mock(AuthorizerInterface::class);
        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);

        $this->service = $this->getClassPartialMock(
            ReplyAuthorizationValidator::class,
            [$this->authorizer, $this->participantProvider],
            ['getContext']
        );
    }

    /**
     * @test
     */
    public function validate(): void
    {
        $this->expectNotToPerformAssertions();

        $value        = \Mockery::mock(MessageInterface::class);
        $thread       = \Mockery::mock(ThreadInterface::class);
        $constraint   = \Mockery::mock(ReplyAuthorization::class);
        $context      = \Mockery::mock(ExecutionContextInterface::class);
        $participant  = \Mockery::mock(ParticipantInterface::class);

        $otherParticipent = \Mockery::mock(ParticipantInterface::class);

        $this->service->shouldReceive('getContext')
            ->once()
            ->withNoArgs()
            ->andReturn($context);

        $constraint->message = 'Some message';

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->withNoArgs()
            ->andReturn($participant);

        $thread->shouldReceive('getOtherParticipants')
            ->once()
            ->with($participant)
            ->andReturn([$otherParticipent]);

        $context->shouldReceive('addViolation')->once()->with($constraint->message);
        $value->shouldReceive('getThread')->once()->andReturn($thread);

        $this->authorizer->shouldReceive('canMessageParticipant')
            ->once()
            ->with($otherParticipent)
            ->andReturnFalse();

        $this->service->validate($value, $constraint);
    }
}
