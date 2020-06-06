<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\FormHandler;

use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\FormHandler\NewThreadMessageFormHandler;
use FOS\MessageBundle\FormHandler\ReplyMessageFormHandler;
use FOS\MessageBundle\FormModel\ReplyMessage;
use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewThreadMessageFormHandler
 * @package FOS\MessageBundle\FormHandler
 */
class ReplyMessageFormHandlerTest extends AbstractTestCase
{
    /**
     * @var ComposerInterface
     */
    protected $composer;

    /**
     * @var SenderInterface
     */
    protected $sender;

    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * @var NewThreadMessageFormHandler
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->composer = \Mockery::mock(ComposerInterface::class);
        $this->sender = \Mockery::mock(SenderInterface::class);
        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);

        $this->service = new ReplyMessageFormHandler(
            $this->composer,
            $this->sender,
            $this->participantProvider
        );
    }

    /**
     * @test
     */
    public function process(): void
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('getMethod')->once()->andReturn('POST');

        $form = \Mockery::mock(FormInterface::class);

        $message = \Mockery::mock(ReplyMessage::class);


        $recipient = \Mockery::mock(ParticipantInterface::class);
        $sender = \Mockery::mock(ParticipantInterface::class);

        $thread = \Mockery::mock(ThreadInterface::class);

        $message->shouldReceive('getBody')->once()->andReturn('body');
        $message->shouldReceive('getThread')->once()->andReturn($thread);

        $form->shouldReceive('handleRequest')->once()->with($request);
        $form->shouldReceive('isValid')->once()->andReturnTrue();
        $form->shouldReceive('getData')->once()->andReturn($message);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($sender);

        $builder = \Mockery::mock(AbstractMessageBuilder::class);

        $this->composer->shouldReceive('reply')
            ->once($thread)
            ->andReturn($builder);

        $builder->shouldReceive('setSender')
            ->once()
            ->with($sender)
            ->andReturn($builder);

        $builder->shouldReceive('setBody')
            ->once()
            ->with('body')
            ->andReturn($builder);

        $messageEntity = \Mockery::mock(MessageInterface::class);

        $builder->shouldReceive('getMessage')
            ->once()
            ->andReturn($messageEntity);

        $this->sender->shouldReceive('send')->once()->with($messageEntity);

        $actual = $this->service->process($form, $request);

        self::assertSame($messageEntity, $actual);
    }
}
