<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Functional;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RegisteringManagersPass
 * @package FOS\MessageBundle\Tests\Functional
 */
class RegisteringManagersPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition('security.access.decision_manager')->setPublic(true);
    }
}
