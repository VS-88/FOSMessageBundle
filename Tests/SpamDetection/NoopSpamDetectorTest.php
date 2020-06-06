<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\SpamDetection;

use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\SpamDetection\NoopSpamDetector;
use FOS\MessageBundle\Tests\AbstractTestCase;

/**
 * Class NoopSpamDetectorTest
 * @package FOS\MessageBundle\Tests\SpamDetection
 */
class NoopSpamDetectorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function isSpam(): void
    {
        $message = \Mockery::mock(NewThreadMessage::class);

        self::assertFalse((new NoopSpamDetector())->isSpam($message));
    }
}
