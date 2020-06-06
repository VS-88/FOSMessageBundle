<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Controller;

use FOS\MessageBundle\Controller\ThreadSearchController;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Search\FinderInterface;
use FOS\MessageBundle\Search\Query;
use FOS\MessageBundle\Search\QueryFactoryInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Tests\PartialMockAwareTrait;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThreadSearchControllerTest
 */
class ThreadSearchControllerTest extends AbstractTestCase
{
    use PartialMockAwareTrait;

    /**
     * @var QueryFactoryInterface
     */
    private $queryFactory;

    /**
     * @var FinderInterface
     */
    private $finder;

    /**
     * @var ThreadSearchController|MockInterface
     */
    private $controller;

    /**
     * ThreadSearchController constructor.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->queryFactory = \Mockery::mock(QueryFactoryInterface::class);
        $this->finder       = \Mockery::mock(FinderInterface::class);

        $this->controller = $this->getClassPartialMock(
            ThreadSearchController::class,
            [
                $this->queryFactory,
                $this->finder
            ],
            [
                'render'
            ]
        );
    }

    /**
     * @test
     */
    public function indexAction(): void
    {
        $response = \Mockery::mock(Response::class);
        $request = \Mockery::mock(Request::class);
        $query   = \Mockery::mock(Query::class);

        $threads = [\Mockery::mock(ThreadInterface::class)];

        $this->queryFactory->shouldReceive('createFromRequest')
            ->once()
            ->with($request)
            ->andReturn($query);

        $this->finder->shouldReceive('find')
            ->once()
            ->with($query)
            ->andReturn($threads);

        $this->controller->shouldReceive('render')
            ->once()
            ->with(
                '@FOSMessage/Message/search.html.twig',
                [
                    'query' => $query,
                    'threads' => $threads,
                ]
            )
            ->andReturn($response);

        self::assertSame($response, $this->controller->indexAction($request));
    }
}
