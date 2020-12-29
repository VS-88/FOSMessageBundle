<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Controller;

use FOS\MessageBundle\DataFixtures\Message\MessageFixture;
use FOS\MessageBundle\DataFixtures\MessageAttachment\MessageAttachmentFixture;
use FOS\MessageBundle\DataFixtures\MessageMetadata\MessageMetadataFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\DataFixtures\Thread\ThreadFixture;
use FOS\MessageBundle\DataFixtures\ThreadMetadata\ThreadMetadataFixture;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Entity\Thread;
use FOS\MessageBundle\Tests\AbstractDataBaseTestCase;
use FOS\MessageBundle\Tests\Functional\DummyAccessDecisionManager;

/**
 * Class ThreadCreateController
 */
class ThreadViewControllerTest extends AbstractDataBaseTestCase
{
    public const FIXTURES = [
        ParticipantFixture::class,
        ThreadFixture::class,
        MessageFixture::class,
        ThreadMetadataFixture::class,
        MessageMetadataFixture::class,
        MessageAttachmentFixture::class,
    ];

    /**
     * @var Thread|object|null
     */
    private $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_2, '', []);

        $this->thread = $this->em->getRepository(Thread::class)->findOneBy(['subject' => 'test']);
    }

    /**
     * @test
     */
    public function getRequestIndexAction(): void
    {
        $this->testContainer->set('security.access.decision_manager', new DummyAccessDecisionManager());

        $this->kernelBrowser->request('GET', '/' . $this->thread->getId());

        $response = $this->kernelBrowser->getResponse();

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function replyThread(): void
    {
        $this->testContainer->set('security.access.decision_manager', new DummyAccessDecisionManager());

        $this->kernelBrowser->disableReboot();

        $uri = '/' . $this->thread->getId();

        $this->kernelBrowser->request('GET', $uri);

        $form = $this->kernelBrowser->getCrawler()->filter('button')->form();

        $values = $form->getPhpValues();

        $values['message']['body']      = 'test body 123';

        $this->kernelBrowser->request(
            'POST',
            $uri,
            $values
        );

        $message = $this->em
            ->getRepository(Message::class)
            ->findOneBy(['body' => $values['message']['body']]);

        self::assertSame(
            $values['message']['body'],
            $message->getBody()
        );
    }

    /**
     * @return array
     */
    protected function getFixtures(): array
    {
        return self::FIXTURES;
    }
}
