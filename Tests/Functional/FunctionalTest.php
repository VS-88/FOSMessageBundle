<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

use FOS\MessageBundle\DataFixtures\Message\MessageFixture;
use FOS\MessageBundle\DataFixtures\MessageMetadata\MessageMetadataFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\DataFixtures\Thread\ThreadFixture;
use FOS\MessageBundle\DataFixtures\ThreadMetadata\ThreadMetadataFixture;
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

    /**
     * @test
     */
    public function controllerSent(): void
    {
        $this->kernelBrowser->request('GET', '/sent');

        $response = $this->kernelBrowser->getResponse();
        static::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function controllerInbox(): void
    {
        $this->kernelBrowser->request('GET', '/inbox');

        $response = $this->kernelBrowser->getResponse();
        static::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function controllerDeleted(): void
    {

        $this->kernelBrowser->request('GET', '/deleted');

        $response = $this->kernelBrowser->getResponse();
        static::assertEquals(200, $response->getStatusCode());
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
