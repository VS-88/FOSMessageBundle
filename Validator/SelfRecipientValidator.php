<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class SelfRecipientValidator
 * @package FOS\MessageBundle\Validator
 */
class SelfRecipientValidator extends ConstraintValidator
{
    use ExecutionContextAwareTrait;

    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * SelfRecipientValidator constructor.
     * @param ParticipantProviderInterface $participantProvider
     */
    public function __construct(ParticipantProviderInterface $participantProvider)
    {
        $this->participantProvider = $participantProvider;
    }

    /**
     * Indicates whether the constraint is valid.
     *
     * @param object|ParticipantInterface $recipient
     * @param Constraint $constraint
     */
    public function validate($recipient, Constraint $constraint)
    {
        if ($recipient === $this->participantProvider->getAuthenticatedParticipant()) {
            $this->getContext()->addViolation($constraint->message);
        }
    }
}
