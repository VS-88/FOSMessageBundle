<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\FormHandler;

use Exception;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\FormHandler\NewThreadMessageFormHandler;
use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;
use FOS\MessageBundle\Model\MessageAttachmentFactoryInterface;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
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
     * @var UploadedFileManagerInterface
     */
    protected $uploadedFileManager;

    /**
     * @var string
     */
    protected $pathToAttachmentsDir;

    /**
     * @var MessageAttachmentFactoryInterface
     */
    protected $messageAttachmentFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->composer = Mockery::mock(ComposerInterface::class);
        $this->sender = Mockery::mock(SenderInterface::class);
        $this->participantProvider = Mockery::mock(ParticipantProviderInterface::class);
        $this->uploadedFileManager = Mockery::mock(UploadedFileManagerInterface::class);
        $this->messageAttachmentFactory = Mockery::mock(MessageAttachmentFactoryInterface::class);
        $this->pathToAttachmentsDir = '/path/to/attachments/dir';

        $this->service = new NewThreadMessageFormHandler(
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
     * @throws Exception
     */
    public function process(): void
    {
        $request = Mockery::mock(Request::class);

        $form = Mockery::mock(FormInterface::class);

        $message = Mockery::mock(NewThreadMessage::class);

        $recipient = Mockery::mock(ParticipantInterface::class);
        $sender = Mockery::mock(ParticipantInterface::class);

        $attachment = Mockery::mock(UploadedFile::class);

        $message->shouldReceive('getSubject')->once()->andReturn(...['subject']);
        $message->shouldReceive('getRecipient')->once()->andReturn(...[$recipient]);
        $message->shouldReceive('getBody')->once()->andReturn(...['body']);
        $message->shouldReceive('getAttachments')->once()->andReturn(...[[$attachment]]);

        $form->shouldReceive('handleRequest')->once()->with(...[$request]);
        $form->shouldReceive('isValid')->once()->andReturnTrue();
        $form->shouldReceive('isSubmitted')->once()->andReturnTrue();
        $form->shouldReceive('getData')->once()->andReturn(...[$message]);

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
            ->andReturn(...[$sender]);

        $builder = Mockery::mock(AbstractMessageBuilder::class);

        $this->composer->shouldReceive('newThread')->once()->andReturn(...[$builder]);

        $builder->shouldReceive('setSubject')
            ->once()
            ->with(...['subject'])
            ->andReturn(...[$builder]);

        $builder->shouldReceive('addRecipient')
            ->once()
            ->with(...[$recipient])
            ->andReturn(...[$builder]);

        $builder->shouldReceive('setSender')
            ->once()
            ->with(...[$sender])
            ->andReturn(...[$builder]);

        $builder->shouldReceive('setBody')
            ->once()
            ->with(...['body'])
            ->andReturn(...[$builder]);

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

        $this->sender->shouldReceive('send')->once()->with(...[$messageEntity]);

        $actual = $this->service->process($form, $request);

        self::assertSame($messageEntity, $actual);
    }
}
