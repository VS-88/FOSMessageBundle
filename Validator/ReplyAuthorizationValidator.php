<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ReplyAuthorizationValidator
 * @package FOS\MessageBundle\Validator
 */
class ReplyAuthorizationValidator extends ConstraintValidator
{
    use ExecutionContextAwareTrait;

    /**
     * @var AuthorizerInterface
     */
    protected $authorizer;

    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * ReplyAuthorizationValidator constructor.
     * @param AuthorizerInterface $authorizer
     * @param ParticipantProviderInterface $participantProvider
     */
    public function __construct(AuthorizerInterface $authorizer, ParticipantProviderInterface $participantProvider)
    {
        $this->authorizer = $authorizer;
        $this->participantProvider = $participantProvider;
    }

    /**
     * Indicates whether the constraint is valid.
     *
     * @param object|MessageInterface     $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $sender = $this->participantProvider->getAuthenticatedParticipant();
        $recipients = $value->getThread()->getOtherParticipants($sender);

        foreach ($recipients as $recipient) {
            if (!$this->authorizer->canMessageParticipant($recipient)) {
                $this->getContext()->addViolation($constraint->message);

                return;
            }
        }
    }
}
