<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Search;

use FOS\MessageBundle\Search\Query;
use FOS\MessageBundle\Search\QueryFactory;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gets the search term from the request and prepares it.
 */
class QueryFactoryTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function createFromRequest(): void
    {
        $queryParameter = 'q';
        $request = \Mockery::mock(Request::class);
        $request->query = \Mockery::mock(ParameterBag::class);

        $str = 'some search string ';

        $request->query->shouldReceive('get')->once()->with($queryParameter)->andReturn($str);

        $escaped = 'escaped string';

        $expected = new Query('some search string', $escaped);

        /**
         * @var QueryFactory $factory
         */
        $factory = $this->getClassPartialMock(
            QueryFactory::class,
            [$queryParameter],
            ['escapeTerm']
        );

        $factory->shouldReceive('escapeTerm')
            ->once()
            ->with('some search string')
            ->andReturn($escaped);

        $actual = $factory->createFromRequest($request);

        self::assertSame(serialize($expected), serialize($actual));
    }
}
