<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

use FOS\MessageBundle\DataFixtures\Message\MessageFixture;
use FOS\MessageBundle\DataFixtures\MessageAttachment\MessageAttachmentFixture;
use FOS\MessageBundle\DataFixtures\MessageMetadata\MessageMetadataFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\DataFixtures\Thread\ThreadFixture;
use FOS\MessageBundle\DataFixtures\ThreadMetadata\ThreadMetadataFixture;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Entity\MessageAttachment;
use FOS\MessageBundle\Tests\AbstractDataBaseTestCase;

/**
 * Class ModerateMessageFunctionalTest
 */
class ModerateMessageFunctionalTest extends AbstractDataBaseTestCase
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
    public function moderationFormAccessSuccess(): void
    {
        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_2, '', []);

        $repo = $this->em->getRepository(Message::class);

        /**
         * @var MessageAttachment $entity
         */
        $entity = $repo->findOneBy(['isModerated' => false]);

        $this->kernelBrowser->request('GET', '/message/moderate/' . $entity->getId());

        $response = $this->kernelBrowser->getResponse();
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function moderationFormAccessNotFoundCase(): void
    {
        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_2, '', []);

        $this->kernelBrowser->request('GET', '/message/moderate/0');

        $response = $this->kernelBrowser->getResponse();
        static::assertSame(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function updateIsModeratedFlagSuccess(): void
    {
        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_2, '', []);

        $repo = $this->em->getRepository(Message::class);

        $this->kernelBrowser->disableReboot();

        /**
         * @var Message $entity
         */
        $entity = $repo->findOneBy(['isModerated' => false]);

        $uri = '/message/moderate/' . $entity->getId();

        $this->kernelBrowser->request('GET', $uri);

        $form = $this->kernelBrowser->getCrawler()->filter('button')->form();

        $values = $form->getPhpValues();

        $values['moderate_message_form']['isApproved'] = true;

        $this->kernelBrowser->request(
            'POST',
            $uri,
            $values
        );

        $entity = $repo->find(
            $entity->getId()
        );

        self::assertTrue(
            $entity->getIsModerated()
        );
    }

    /**
     * @return string[]
     */
    protected function getFixtures(): array
    {
        return self::FIXTURES;
    }
}
