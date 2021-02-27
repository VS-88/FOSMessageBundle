<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Controller;

use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Tests\AbstractDataBaseTestCase;

/**
 * Class ThreadCreateController
 */
class ThreadCreateControllerTest extends AbstractDataBaseTestCase
{
    public const FIXTURES = [
        ParticipantFixture::class,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn(ParticipantFixture::PARTICIPANT_EMAIL_2, '', []);
    }

    /**
     * @test
     */
    public function getRequestIndexAction(): void
    {
        $this->kernelBrowser->request('GET', '/new');

        $response = $this->kernelBrowser->getResponse();

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function createThread(): void
    {
        $this->kernelBrowser->disableReboot();

        $uri = '/new';

        $this->kernelBrowser->request('GET', $uri);

        $form = $this->kernelBrowser->getCrawler()->filter('button')->form();

        $values = $form->getPhpValues();

        $values['message']['subject']   = 'test subject';
        $values['message']['body']      = 'test body';
        $values['message']['recipient'] = ParticipantFixture::PARTICIPANT_EMAIL_1;


        $this->kernelBrowser->request(
            'POST',
            $uri,
            $values
        );

        $messages = $this->em->getRepository(Message::class)->findAll();

        self::assertCount(
            1,
            $messages
        );

        self::assertSame(
            'test body',
            $messages[0]->getBody()
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
