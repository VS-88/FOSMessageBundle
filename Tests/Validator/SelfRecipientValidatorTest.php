<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Validator;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Validator\SelfRecipient;
use FOS\MessageBundle\Validator\SelfRecipientValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class SelfRecipientValidator
 * @package FOS\MessageBundle\Validator
 */
class SelfRecipientValidatorTest extends AbstractTestCase
{
    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;
    /**
     * @var SelfRecipientValidator
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);

        $this->service = $this->getClassPartialMock(
            SelfRecipientValidator::class,
            [$this->participantProvider],
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
        $constraint = \Mockery::mock(SelfRecipient::class);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->withNoArgs()
            ->andReturn($recipient);

        $context      = \Mockery::mock(ExecutionContextInterface::class);

        $this->service->shouldReceive('getContext')
            ->once()
            ->withNoArgs()
            ->andReturn($context);

        $constraint->message = 'Some message';

        $context->shouldReceive('addViolation')->once()->with($constraint->message);

        $this->service->validate($recipient, $constraint);
    }
}
