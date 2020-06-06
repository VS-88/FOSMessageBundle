<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Spam
 * @package FOS\MessageBundle\Validator
 */
class Spam extends Constraint
{
    public $message = 'fos_user.body.spam';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return 'fos_message.validator.spam';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
