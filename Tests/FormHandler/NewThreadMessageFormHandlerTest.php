<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\FormHandler;

use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\FormHandler\NewThreadMessageFormHandler;
use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewThreadMessageFormHandler
 * @package FOS\MessageBundle\FormHandler
 */
class NewThreadMessageFormHandlerTest extends AbstractTestCase
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

    /**
     * @param ComposerInterface            $composer
     * @param SenderInterface              $sender
     * @param ParticipantProviderInterface $participantProvider
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->composer = \Mockery::mock(ComposerInterface::class);
        $this->sender = \Mockery::mock(SenderInterface::class);
        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);

        $this->service = new NewThreadMessageFormHandler(
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

        $message = \Mockery::mock(NewThreadMessage::class);

        $recipient = \Mockery::mock(ParticipantInterface::class);
        $sender = \Mockery::mock(ParticipantInterface::class);

        $message->shouldReceive('getSubject')->once()->andReturn('subject');
        $message->shouldReceive('getRecipient')->once()->andReturn($recipient);
        $message->shouldReceive('getBody')->once()->andReturn('body');

        $form->shouldReceive('handleRequest')->once()->with($request);
        $form->shouldReceive('isValid')->once()->andReturnTrue();
        $form->shouldReceive('getData')->once()->andReturn($message);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($sender);

        $builder = \Mockery::mock(AbstractMessageBuilder::class);

        $this->composer->shouldReceive('newThread')->once()->andReturn($builder);

        $builder->shouldReceive('setSubject')
            ->once()
            ->with('subject')
            ->andReturn($builder);

        $builder->shouldReceive('addRecipient')
            ->once()
            ->with($recipient)
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
