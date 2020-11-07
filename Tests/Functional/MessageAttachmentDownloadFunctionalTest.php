<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

use FOS\MessageBundle\DataFixtures\Message\MessageFixture;
use FOS\MessageBundle\DataFixtures\MessageAttachment\MessageAttachmentFixture;
use FOS\MessageBundle\DataFixtures\MessageMetadata\MessageMetadataFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\DataFixtures\Thread\ThreadFixture;
use FOS\MessageBundle\DataFixtures\ThreadMetadata\ThreadMetadataFixture;
use FOS\MessageBundle\Entity\MessageAttachment;
use FOS\MessageBundle\Tests\AbstractDataBaseTestCase;

/**
 * Class MessageAttachmentDownloadFunctionalTest
 */
class MessageAttachmentDownloadFunctionalTest extends AbstractDataBaseTestCase
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
     * @test
     */
    public function downloadAccessGranted(): void
    {
        $repo = $this->em->getRepository(MessageAttachment::class);

        /**
         * @var MessageAttachment $entity
         */
        $entity = $repo->findOneBy(['fileName' => MessageAttachmentFixture::REFERENCE_MESSAGE_ATTACHMENT_1_FILE_NAME]);
        $this->testContainer->set('security.access.decision_manager', new DummyAccessDecisionManager());

        $this->kernelBrowser->request('GET', '/message_attachment/download/' . $entity->getId());

        $response = $this->kernelBrowser->getResponse();
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function downloadAccessDenied(): void
    {
        $repo = $this->em->getRepository(MessageAttachment::class);

        /**
         * @var MessageAttachment $entity
         */
        $entity = $repo->findOneBy(['fileName' => MessageAttachmentFixture::REFERENCE_MESSAGE_ATTACHMENT_1_FILE_NAME]);

        $this->kernelBrowser->request('GET', '/message_attachment/download/' . $entity->getId());

        $response = $this->kernelBrowser->getResponse();
        static::assertSame(403, $response->getStatusCode());
    }

    /**
     * @return string[]
     */
    protected function getFixtures(): array
    {
        return self::FIXTURES;
    }

    /**
     * @return array
     */
    protected function getClientServerParams(): array
    {
        return [
            'PHP_AUTH_USER' => 'guilhem',
            'PHP_AUTH_PW' => 'pass',
        ];
    }
}
