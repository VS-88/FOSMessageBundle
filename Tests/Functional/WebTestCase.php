<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use FOS\MessageBundle\Tests\Functional\TestKernel;

/**
 * Class WebTestCase
 * @package FOS\MessageBundle\Tests\Functional
 */
class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
