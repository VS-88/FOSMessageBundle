<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Functional;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 * Class DummyAccessDecisionManager
 * @package FOS\MessageBundle\Tests\Functional
 */
class DummyAccessDecisionManager implements AccessDecisionManagerInterface
{

    /**
     * @inheritDoc
     */
    public function decide(TokenInterface $token, array $attributes, $object = null)
    {
        return true;
    }
}
