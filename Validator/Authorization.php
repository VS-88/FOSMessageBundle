<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Authorization
 * @package FOS\MessageBundle\Validator
 */
class Authorization extends Constraint
{
    public $message = 'fos_message.not_authorized';

    /**
     * {@inheritDoc}
     */
    public function validatedBy(): string
    {
        return 'fos_message.validator.authorization';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return static::PROPERTY_CONSTRAINT;
    }
}
