<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class SelfRecipient
 * @package FOS\MessageBundle\Validator
 */
class SelfRecipient extends Constraint
{
    public $message = 'fos_message.self_recipient';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return 'fos_message.validator.self_recipient';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return static::PROPERTY_CONSTRAINT;
    }
}
