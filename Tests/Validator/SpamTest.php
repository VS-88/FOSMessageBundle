<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Validator;

use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Validator\Spam;

/**
 * Class SpamTest
 * @package FOS\MessageBundle\Tests\Validator
 */
class SpamTest extends AbstractTestCase
{
    /**
     * @var Spam
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new Spam();
    }

    /**
     * @test
     */
    public function validatedBy(): void
    {
        self::assertSame('fos_message.validator.spam', $this->service->validatedBy());
    }

    /**
     * @test
     */
    public function getTargets(): void
    {
        self::assertSame($this->service::CLASS_CONSTRAINT, $this->service->getTargets());
    }

    /**
     * @test
     */
    public function message(): void
    {
        self::assertSame('fos_user.body.spam', $this->service->message);
    }
}
