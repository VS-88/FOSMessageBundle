<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

/**
 * Class FunctionalTest
 */
class FunctionalTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function controllerSent(): void
    {
        $client = self::createClient([], [
            'PHP_AUTH_USER' => 'guilhem',
            'PHP_AUTH_PW' => 'pass',
        ]);

        $client->request('GET', '/sent');

        $response = $client->getResponse();
        static::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function controllerInbox(): void
    {
        $client = self::createClient([], [
            'PHP_AUTH_USER' => 'guilhem',
            'PHP_AUTH_PW' => 'pass',
        ]);

        $client->request('GET', '/inbox');

        $response = $client->getResponse();
        static::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function controllerDeleted(): void
    {
        $client = self::createClient([], [
            'PHP_AUTH_USER' => 'guilhem',
            'PHP_AUTH_PW' => 'pass',
        ]);

        $client->request('GET', '/deleted');

        $response = $client->getResponse();
        static::assertEquals(200, $response->getStatusCode());
    }
}
