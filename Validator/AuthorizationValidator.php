<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class AuthorizationValidator
 * @package FOS\MessageBundle\Validator
 */
class AuthorizationValidator extends ConstraintValidator
{
    use ExecutionContextAwareTrait;

    /**
     * @var AuthorizerInterface
     */
    protected $authorizer;

    /**
     * AuthorizationValidator constructor.
     * @param AuthorizerInterface $authorizer
     */
    public function __construct(AuthorizerInterface $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * Indicates whether the constraint is valid.
     *
     * @param object|ParticipantInterface     $recipient
     * @param Constraint $constraint
     */
    public function validate($recipient, Constraint $constraint)
    {
        if ($recipient && !$this->authorizer->canMessageParticipant($recipient)) {
            $this->getContext()->addViolation($constraint->message);
        }
    }
}
