<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\SpamDetection\SpamDetectorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class SpamValidator
 * @package FOS\MessageBundle\Validator
 */
class SpamValidator extends ConstraintValidator
{
    use ExecutionContextAwareTrait;

    /**
     * @var SpamDetectorInterface
     */
    protected $spamDetector;

    /**
     * SpamValidator constructor.
     * @param SpamDetectorInterface $spamDetector
     */
    public function __construct(SpamDetectorInterface $spamDetector)
    {
        $this->spamDetector = $spamDetector;
    }

    /**
     * Indicates whether the constraint is valid.
     *
     * @param object|NewThreadMessage     $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->spamDetector->isSpam($value)) {
            $this->getContext()->addViolation($constraint->message);
        }
    }
}
