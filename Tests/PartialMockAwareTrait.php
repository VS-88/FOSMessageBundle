<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests;

use Mockery\MockInterface;
use \Mockery as m;

/**
 * Trait PartialMockAwareTrait
 */
trait PartialMockAwareTrait
{
    /**
     * @param string $classToMock
     * @param array $constructorArgs
     * @param array $methodsToMock
     *
     * @return MockInterface
     */
    public function getClassPartialMock(
        string $classToMock,
        array $constructorArgs,
        array $methodsToMock
    ): MockInterface {
        $methodsToMockAsString = '['. implode(',', $methodsToMock) . ']';

        $mock = m::mock($classToMock. $methodsToMockAsString, $constructorArgs);

        $mock->shouldAllowMockingProtectedMethods();

        return $mock;
    }
}
