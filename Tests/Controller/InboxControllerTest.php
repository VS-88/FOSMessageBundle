<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Controller;

use FOS\MessageBundle\Controller\InboxController;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Tests\PartialMockAwareTrait;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InboxControllerTest
 */
class InboxControllerTest extends AbstractTestCase
{
    use PartialMockAwareTrait;

    private const PATH_TO_TEMPLATE = '/path/to/template';

    /**
     * @var ProviderInterface|LegacyMockInterface|MockInterface
     */
    private $provider;

    /**
     * @var InboxController|MockInterface
     */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = \Mockery::mock(ProviderInterface::class);

        $this->controller = $this->getClassPartialMock(
            InboxController::class,
            [
                $this->provider,
                self::PATH_TO_TEMPLATE
            ],
            [
                'render'
            ]
        );
    }

    /**
     * @test
     */
    public function inboxAction(): void
    {
        $response = \Mockery::mock(Response::class);
        $threads = [\Mockery::mock(ThreadInterface::class)];

        $this->provider->shouldReceive('getInboxThreads')
            ->once()
            ->withNoArgs()
            ->andReturn($threads);

        $this->controller->shouldReceive('render')
            ->once()
            ->with(
                self::PATH_TO_TEMPLATE,
                [
                    'threads' => $threads,
                ]
            )
            ->andReturn($response);

        $actual = $this->controller->inboxAction();

        self::assertSame($response, $actual);
    }
}
