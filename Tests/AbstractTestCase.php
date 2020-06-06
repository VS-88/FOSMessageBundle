<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @param string $classToMock
     * @param array $constructorArgs
     * @param array $methodsToMock
     *
     * @return MockInterface
     */
    protected function getClassPartialMock(
        string $classToMock,
        array $constructorArgs,
        array $methodsToMock
    ): MockInterface {
        $methodsToMockAsString = '['. implode(',', $methodsToMock) . ']';

        $mock = \Mockery::mock($classToMock. $methodsToMockAsString, $constructorArgs);

        $mock->shouldAllowMockingProtectedMethods();

        return $mock;
    }
}
