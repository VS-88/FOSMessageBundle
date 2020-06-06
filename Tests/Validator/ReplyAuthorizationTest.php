<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Validator;

use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Validator\Authorization;
use FOS\MessageBundle\Validator\ReplyAuthorization;

/**
 * Class ReplyAuthorizationTest
 */
class ReplyAuthorizationTest extends AbstractTestCase
{
    /**
     * @var Authorization
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ReplyAuthorization();
    }

    /**
     * @test
     */
    public function validatedBy(): void
    {
        self::assertSame('fos_message.validator.reply_authorization', $this->service->validatedBy());
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
        self::assertSame('fos_message.reply_not_authorized', $this->service->message);
    }
}
