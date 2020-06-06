<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Validator;

use FOS\MessageBundle\Tests\AbstractTestCase;
use FOS\MessageBundle\Validator\SelfRecipient;

/**
 * Class SelfRecipientTest
 */
class SelfRecipientTest extends AbstractTestCase
{
    /**
     * @var SelfRecipient
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new SelfRecipient();
    }

    /**
     * @test
     */
    public function validatedBy(): void
    {
        self::assertSame('fos_message.validator.self_recipient', $this->service->validatedBy());
    }

    /**
     * @test
     */
    public function getTargets(): void
    {
        self::assertSame($this->service::PROPERTY_CONSTRAINT, $this->service->getTargets());
    }

    /**
     * @test
     */
    public function message(): void
    {
        self::assertSame('fos_message.self_recipient', $this->service->message);
    }
}
