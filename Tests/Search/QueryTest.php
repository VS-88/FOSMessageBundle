<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Search;

use FOS\MessageBundle\Search\Query;
use FOS\MessageBundle\Tests\AbstractTestCase;

/**
 * Search term.
 */
class QueryTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function query(): void
    {
        $q = new Query(
            'original',
            'escaped'
        );

        self::assertSame('escaped', $q->getEscaped());
        self::assertSame('original', $q->getOriginal());
        self::assertFalse($q->isEmpty());
        self::assertSame('original', (string) $q);
    }
}
