<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Controller;

use FOS\MessageBundle\Controller\ThreadCreateController;
use FOS\MessageBundle\Controller\ThreadViewController;
use FOS\MessageBundle\FormFactory\AbstractMessageFormFactory;
use FOS\MessageBundle\FormHandler\AbstractMessageFormHandler;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Tests\PartialMockAwareTrait;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ThreadCreateController
 */
class ThreadViewControllerTest extends AbstractTestCase
{
    use PartialMockAwareTrait;

    private const PATH_TO_TEMPLATE = '/path/to/template';

    /**
     * @var ThreadViewController|MockInterface
     */
    private $controller;

    /**
     * @var AbstractMessageFormFactory
     */
    private $newMessageFormFactory;

    /**
     * @var AbstractMessageFormHandler
     */
    private $newFormHandler;
    /**
     * @var ProviderInterface|\Mockery\LegacyMockInterface|MockInterface
     */
    private $provider;


    protected function setUp(): void
    {
        parent::setUp();

        $this->provider              = \Mockery::mock(ProviderInterface::class);
        $this->newMessageFormFactory = \Mockery::mock(AbstractMessageFormFactory::class);
        $this->newFormHandler        = \Mockery::mock(AbstractMessageFormHandler::class);

        $this->controller = $this->getClassPartialMock(
            ThreadViewController::class,
            [
                $this->provider,
                self::PATH_TO_TEMPLATE,
                $this->newMessageFormFactory,
                $this->newFormHandler
            ],
            [
                'render',
                'redirectToRoute'
            ]
        );
    }

    /**
     * @test
     */
    public function indexActionPostCase(): void
    {
        $id = 5;
        $request = \Mockery::mock(Request::class);

        $form = \Mockery::mock(FormInterface::class);
        $thread = \Mockery::mock(ThreadInterface::class);

        $this->provider->shouldReceive('getThread')
            ->once()
            ->with($id)
            ->andReturn($thread);

        $this->newMessageFormFactory->shouldReceive('create')
            ->once()
            ->with($thread)
            ->andReturn($form);

        $message = \Mockery::mock(MessageInterface::class);

        $thread->shouldReceive('getId')->once()->andReturn(1);

        $message->shouldReceive('getThread')->once()->andReturn($thread);

        $this->newFormHandler->shouldReceive('process')
            ->once()
            ->with($form, $request)
            ->andReturn($message);

        $response = \Mockery::mock(RedirectResponse::class);

        $this->controller->shouldReceive('redirectToRoute')
            ->once()
            ->with(
                'fos_message_thread_view',
                [
                    'threadId' => 1,
                ]
            )
            ->andReturn($response);

        $actual = $this->controller->indexAction($request, $id);

        self::assertSame($response, $actual);
    }

    /**
     * @test
     */
    public function indexActionNotPostCase(): void
    {
        $id = 5;
        $request = \Mockery::mock(Request::class);

        $thread = \Mockery::mock(ThreadInterface::class);

        $form = \Mockery::mock(FormInterface::class);

        $viewModel = \Mockery::mock(FormView::class);
        $data = ['some data'];

        $form->shouldReceive('createView')->once()->andReturn($viewModel);

        $this->newMessageFormFactory->shouldReceive('create')
            ->once()
            ->with($thread)
            ->andReturn($form);

        $this->provider->shouldReceive('getThread')
            ->once()
            ->with($id)
            ->andReturn($thread);

        $this->newFormHandler->shouldReceive('process')
            ->once()
            ->with($form, $request)
            ->andReturnNull();

        $response = \Mockery::mock(RedirectResponse::class);

        $this->controller->shouldReceive('render')
            ->once()
            ->with(
                self::PATH_TO_TEMPLATE,
                [
                    'form' => $viewModel,
                    'thread' => $thread,
                ]
            )
            ->andReturn($response);

        $actual = $this->controller->indexAction($request, $id);

        self::assertSame($response, $actual);
    }
}
