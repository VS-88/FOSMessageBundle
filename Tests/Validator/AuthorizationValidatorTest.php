<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Validator;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Validator\Authorization;
use FOS\MessageBundle\Validator\AuthorizationValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class AuthorizationValidator
 * @package FOS\MessageBundle\Validator
 */
class AuthorizationValidatorTest extends AbstractTestCase
{
    /**
     * @var AuthorizerInterface
     */
    protected $authorizer;

    /**
     * @var AuthorizationValidator
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizer = \Mockery::mock(AuthorizerInterface::class);
        $this->service = $this->getClassPartialMock(
            AuthorizationValidator::class,
            [$this->authorizer],
            ['getContext']
        );
    }

    /**
     * @test
     */
    public function validate(): void
    {
        $this->expectNotToPerformAssertions();

        $recipient  = \Mockery::mock(ParticipantInterface::class);
        $constraint = \Mockery::mock(Authorization::class);
        $context    = \Mockery::mock(ExecutionContextInterface::class);

        $this->service->shouldReceive('getContext')
            ->once()
            ->withNoArgs()
            ->andReturn($context);


        $constraint->message = 'Some message';

        $context->shouldReceive('addViolation')->once()->with($constraint->message);

        $this->authorizer->shouldReceive('canMessageParticipant')
            ->once()
            ->with($recipient)
            ->andReturnFalse();

        $this->service->validate($recipient, $constraint);
    }
}
