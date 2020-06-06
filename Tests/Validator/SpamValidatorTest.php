<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Validator;

use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\SpamDetection\SpamDetectorInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Validator\Spam;
use FOS\MessageBundle\Validator\SpamValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class SpamValidator
 * @package FOS\MessageBundle\Validator
 */
class SpamValidatorTest extends AbstractTestCase
{
    /**
     * @var SpamDetectorInterface
     */
    protected $spamDetector;

    /**
     * @var SpamValidator
     */
    private $service;

    protected function setUp(): void
    {
        $this->spamDetector = \Mockery::mock(SpamDetectorInterface::class);

        $this->service = $this->getClassPartialMock(
            SpamValidator::class,
            [$this->spamDetector],
            ['getContext']
        );
    }

    /**
     * @test
     */
    public function validate(): void
    {
        $this->expectNotToPerformAssertions();

        $value      = \Mockery::mock(NewThreadMessage::class);
        $constraint = \Mockery::mock(Spam::class);
        $context    = \Mockery::mock(ExecutionContextInterface::class);

        $this->service->shouldReceive('getContext')
            ->once()
            ->withNoArgs()
            ->andReturn($context);

        $constraint->message = 'Some message';

        $context->shouldReceive('addViolation')->once()->with($constraint->message);

        $this->spamDetector->shouldReceive('isSpam')
            ->once()
            ->with($value)
            ->andReturnTrue();

        $this->service->validate($value, $constraint);
    }
}
