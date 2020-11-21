<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

use FOS\MessageBundle\DataFixtures\Message\MessageFixture;
use FOS\MessageBundle\DataFixtures\MessageMetadata\MessageMetadataFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\DataFixtures\Thread\ThreadFixture;
use FOS\MessageBundle\DataFixtures\ThreadMetadata\ThreadMetadataFixture;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Tests\AbstractDataBaseTestCase;

/**
 * Class FunctionalTest
 */
class FunctionalTest extends AbstractDataBaseTestCase
{
    public const FIXTURES = [
        ParticipantFixture::class,
        ThreadFixture::class,
        MessageFixture::class,
        ThreadMetadataFixture::class,
        MessageMetadataFixture::class,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_2, '', []);
    }

    /**
     * @test
     */
    public function controllerSent(): void
    {
        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_1, '', []);

        $this->kernelBrowser->request('GET', '/sent');

        $response = $this->kernelBrowser->getResponse();

        file_put_contents(__DIR__ . '/log', $response->getContent());

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function controllerInbox(): void
    {
        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_2, '', []);

        /**
         * @var Message[] $messages
         */
        $messages = $this->em->getRepository(Message::class)->findAll();

        $message = reset($messages);

        $message->setIsModerated(true);


        $this->em->persist($message);
        $this->em->flush();
        $this->em->clear();

        $this->kernelBrowser->request('GET', '/inbox');

        $response = $this->kernelBrowser->getResponse();

        file_put_contents(__DIR__ . '/log', $response->getContent());

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function controllerDeleted(): void
    {

        $this->kernelBrowser->request('GET', '/deleted');

        $response = $this->kernelBrowser->getResponse();
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function controllerModeratedList(): void
    {

        $this->kernelBrowser->request('GET', '/message/moderate');

        $response = $this->kernelBrowser->getResponse();
        static::assertSame(200, $response->getStatusCode());
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
        return [];
    }
}
