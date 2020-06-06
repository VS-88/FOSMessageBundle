<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Trait ExecutionContextAwareTrait
 * @package FOS\MessageBundle\Tests\Validator
 */
trait ExecutionContextAwareTrait
{
    /**
     * @return ExecutionContextInterface
     */
    protected function getContext(): ExecutionContextInterface
    {
        return $this->context;
    }
}
