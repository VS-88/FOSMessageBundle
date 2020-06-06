<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Search;

use Doctrine\ORM\QueryBuilder;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Search\Finder;
use FOS\MessageBundle\Search\Query;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;

/**
 * Finds threads of a participant, matching a given query.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class FinderTest extends AbstractTestCase
{
    /**
     * The participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * The thread manager.
     *
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * @var Finder
     */
    private $service;

    protected function setUp(): void
    {
        $this->participantProvider = \Mockery::mock(ParticipantProviderInterface::class);
        $this->threadManager       = \Mockery::mock(ThreadManagerInterface::class);

        $this->service = new Finder(
            $this->participantProvider,
            $this->threadManager
        );
    }

    /**
     * @test
     */
    public function find(): void
    {
        $str = 'some string';

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider
            ->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $query = \Mockery::mock(Query::class);

        $query->shouldReceive('getEscaped')->once()->andReturn($str);

        $expected = ['does not matter'];

        $this->threadManager->shouldReceive('findParticipantThreadsBySearch')
            ->once()
            ->with($participant, $str)
            ->andReturn($expected);

        self::assertSame($expected, $this->service->find($query));
    }

    /**
     * @test
     */
    public function getQueryBuilder(): void
    {
        $str = 'some string';

        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->participantProvider
            ->shouldReceive('getAuthenticatedParticipant')
            ->once()
            ->andReturn($participant);

        $query = \Mockery::mock(Query::class);

        $query->shouldReceive('getEscaped')->once()->andReturn($str);

        $expected = \Mockery::mock(QueryBuilder::class);

        $this->threadManager->shouldReceive('getParticipantThreadsBySearchQueryBuilder')
            ->once()
            ->with($participant, $str)
            ->andReturn($expected);

        self::assertSame($expected, $this->service->getQueryBuilder($query));
    }
}
