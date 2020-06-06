<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Controller;

use FOS\MessageBundle\Controller\ThreadCreateController;
use FOS\MessageBundle\FormFactory\AbstractMessageFormFactory;
use FOS\MessageBundle\FormHandler\AbstractMessageFormHandler;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ThreadInterface;
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
class ThreadCreateControllerTest extends AbstractTestCase
{
    use PartialMockAwareTrait;

    private const PATH_TO_TEMPLATE = '/path/to/template';

    /**
     * @var ThreadCreateController|MockInterface
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


    protected function setUp(): void
    {
        parent::setUp();

        $this->newMessageFormFactory = \Mockery::mock(AbstractMessageFormFactory::class);
        $this->newFormHandler        = \Mockery::mock(AbstractMessageFormHandler::class);

        $this->controller = $this->getClassPartialMock(
            ThreadCreateController::class,
            [
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
        $form = \Mockery::mock(FormInterface::class);
        $request = \Mockery::mock(Request::class);

        $this->newMessageFormFactory->shouldReceive('create')
            ->once()
            ->withNoArgs()
            ->andReturn($form);

        $message = \Mockery::mock(MessageInterface::class);

        $thread = \Mockery::mock(ThreadInterface::class);
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

        $actual = $this->controller->indexAction($request);

        self::assertSame($response, $actual);
    }

    /**
     * @test
     */
    public function indexActionNotPostCase(): void
    {
        $form = \Mockery::mock(FormInterface::class);
        $request = \Mockery::mock(Request::class);

        $viewModel = \Mockery::mock(FormView::class);
        $data = ['some data'];

        $form->shouldReceive('createView')->once()->andReturn($viewModel);
        $form->shouldReceive('getData')->once()->andReturn($data);

        $this->newMessageFormFactory->shouldReceive('create')
            ->once()
            ->withNoArgs()
            ->andReturn($form);

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
                    'data' => $data,
                ]
            )
            ->andReturn($response);

        $actual = $this->controller->indexAction($request);

        self::assertSame($response, $actual);
    }
}
