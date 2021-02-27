<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Exceptions;

use Exception;
use Symfony\Component\Form\FormError;
use Throwable;

/**
 * Class SubmittedMessageValidationException
 * @package FOS\MessageBundle\Exceptions
 */
class SubmittedMessageValidationException extends Exception
{
    /**
     * @var iterable|FormError[]
     */
    private $formErrors;

    /**
     * SubmittedMessageValidationException constructor.
     * @param iterable $formErrors
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(iterable $formErrors, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->formErrors = $formErrors;
    }

    /**
     * @return iterable|FormError[]
     */
    public function getFormErrors(): iterable
    {
        return $this->formErrors;
    }
}
