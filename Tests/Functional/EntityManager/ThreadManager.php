<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional\EntityManager;

use Doctrine\ORM\QueryBuilder;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\ThreadManager as BaseThreadManager;
use FOS\MessageBundle\Tests\Functional\Entity\Thread;
use Mockery;

/**
 * Class ThreadManager
 * @package FOS\MessageBundle\Tests\Functional\EntityManager
 */
class ThreadManager extends BaseThreadManager
{
    /**
     * {@inheritDoc}
     */
    public function findThreadById($id): ?ThreadInterface
    {
        return new Thread();
    }

    /**
     * {@inheritDoc}
     */
    public function getParticipantInboxThreadsQueryBuilder(ParticipantInterface $participant): QueryBuilder
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * {@inheritDoc}
     */
    public function findParticipantInboxThreads(ParticipantInterface $participant): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getParticipantSentThreadsQueryBuilder(ParticipantInterface $participant): QueryBuilder
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * {@inheritDoc}
     */
    public function findParticipantSentThreads(ParticipantInterface $participant): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public function getParticipantDeletedThreadsQueryBuilder(ParticipantInterface $participant): QueryBuilder
    {
        return Mockery::mock(QueryBuilder::class);
    }
    /**
     * {@inheritDoc}
     */
    public function findParticipantDeletedThreads(ParticipantInterface $participant): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public function getParticipantThreadsBySearchQueryBuilder(ParticipantInterface $participant, $search): QueryBuilder
    {
        return Mockery::mock(QueryBuilder::class);
    }
    /**
     * {@inheritDoc}
     */
    public function findParticipantThreadsBySearch(ParticipantInterface $participant, $search): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public function findThreadsCreatedBy(ParticipantInterface $participant): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
    }
    /**
     * {@inheritDoc}
     */
    public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
    }
    /**
     * {@inheritDoc}
     */
    public function saveThread(ThreadInterface $thread, $andFlush = true): void
    {
    }
    /**
     * {@inheritDoc}
     */
    public function deleteThread(ThreadInterface $thread): void
    {
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Thread::class;
    }

    public function createThread(): ThreadInterface
    {
        return new Thread();
    }
}
