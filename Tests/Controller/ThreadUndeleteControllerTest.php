<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Controller;

use FOS\MessageBundle\Controller\ThreadUndeleteController;
use FOS\MessageBundle\Deleter\DeleterInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Tests\PartialMockAwareTrait;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ThreadUndeleteControllerTest
 */
class ThreadUndeleteControllerTest extends AbstractTestCase
{
    use PartialMockAwareTrait;

    /**
     * @var DeleterInterface|MockInterface
     */
    protected $deleter;

    /**
     * @var ThreadManagerInterface
     */
    private $threadManager;

    private const PATH_TO_TEMPLATE = '/path/to/template';

    /**
     * @var ProviderInterface|LegacyMockInterface|MockInterface
     */
    private $provider;

    /**
     * @var ThreadUndeleteController|MockInterface
     */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = \Mockery::mock(ProviderInterface::class);
        $this->threadManager = \Mockery::mock(ThreadManagerInterface::class);
        $this->deleter = \Mockery::mock(DeleterInterface::class);

        $this->controller = $this->getClassPartialMock(
            ThreadUndeleteController::class,
            [
                $this->provider,
                $this->deleter,
                $this->threadManager
            ],
            [
                'redirectToRoute'
            ]
        );
    }

    /**
     * @test
     */
    public function indexAction(): void
    {
        $id = 1;
        $response = \Mockery::mock(RedirectResponse::class);
        $thread = \Mockery::mock(ThreadInterface::class);

        $this->provider->shouldReceive('getThread')
            ->once()
            ->with($id)
            ->andReturn($thread);

        $this->deleter->shouldReceive('markAsUndeleted')
            ->once()
            ->with($thread)
            ->andReturnNull();

        $this->threadManager->shouldReceive('saveThread')
            ->once()
            ->with($thread)
            ->andReturnNull();

        $this->controller->shouldReceive('redirectToRoute')
            ->once()
            ->with(
                'fos_message_inbox'
            )
            ->andReturn($response);

        $actual = $this->controller->indexAction($id);

        self::assertSame($response, $actual);
    }
}
