<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\FormHandler;

use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\FormHandler\NewThreadMessageFormHandler;
use FOS\MessageBundle\FormHandler\ReplyMessageFormHandler;
use FOS\MessageBundle\FormModel\ReplyMessage;
use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;
use FOS\MessageBundle\Model\MessageAttachmentFactoryInterface;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Utils\UploadedFileManagerInterface;
use Mockery;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    /**
     * @var UploadedFileManagerInterface|Mockery\MockInterface
     */
    private $uploadedFileManager;
    /**
     * @var MessageAttachmentFactoryInterface|Mockery\MockInterface
     */
    private $messageAttachmentFactory;
    /**
     * @var string
     */
    private $pathToAttachmentsDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->composer = Mockery::mock(ComposerInterface::class);
        $this->sender = Mockery::mock(SenderInterface::class);
        $this->participantProvider = Mockery::mock(ParticipantProviderInterface::class);
        $this->uploadedFileManager      = Mockery::mock(UploadedFileManagerInterface::class);
        $this->messageAttachmentFactory = Mockery::mock(MessageAttachmentFactoryInterface::class);
        $this->pathToAttachmentsDir     = '/path/to/attachments/dir';

        $this->service = new ReplyMessageFormHandler(
            $this->composer,
            $this->sender,
            $this->participantProvider,
            $this->uploadedFileManager,
            $this->messageAttachmentFactory,
            $this->pathToAttachmentsDir
        );
    }

    /**
     * @test
     */
    public function process(): void
    {
        $request = Mockery::mock(Request::class);

        $form = Mockery::mock(FormInterface::class);

        $message = Mockery::mock(ReplyMessage::class);


        $sender = Mockery::mock(ParticipantInterface::class);
        $attachment = Mockery::mock(UploadedFile::class);

        $thread = Mockery::mock(ThreadInterface::class);

        $message->shouldReceive('getBody')->once()->andReturn('body');
        $message->shouldReceive('getThread')->once()->andReturn($thread);
        $message->shouldReceive('getAttachments')->once()->andReturn(...[[$attachment]]);

        $form->shouldReceive('handleRequest')->once()->with($request);
        $form->shouldReceive('isValid')->once()->andReturnTrue();
        $form->shouldReceive('getData')->once()->andReturn($message);
        $form->shouldReceive('isSubmitted')->once()->andReturnTrue();

        $savedFilename = 'some-file-name';

        $this->uploadedFileManager
            ->shouldReceive('moveToDirAndReturnFileNames')
            ->once()
            ->with(
                ...[
                    [$attachment],
                    'attachment',
                    $this->pathToAttachmentsDir
                ]
            )
            ->andReturn(...[[$savedFilename]]);

        $this->participantProvider->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($sender);

        $builder = Mockery::mock(AbstractMessageBuilder::class);

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

        $messageEntity = Mockery::mock(MessageInterface::class);


        $messageEntity->shouldReceive('addMessageAttachments')
            ->once()
            ->with(
                ...[
                    [$savedFilename],
                    $this->messageAttachmentFactory
                ]
            )
            ->andReturn(...[$messageEntity]);

        $builder->shouldReceive('getMessage')
            ->once()
            ->andReturn(...[$messageEntity]);


        $this->sender->shouldReceive('send')->once()->with($messageEntity);

        $actual = $this->service->process($form, $request);

        self::assertSame($messageEntity, $actual);
    }
}
