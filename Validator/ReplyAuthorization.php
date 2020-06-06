<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class ReplyAuthorization
 * @package FOS\MessageBundle\Validator
 */
class ReplyAuthorization extends Constraint
{
    public $message = 'fos_message.reply_not_authorized';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return 'fos_message.validator.reply_authorization';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
