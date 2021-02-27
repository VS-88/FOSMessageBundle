<?php
/** @noinspection PhpParamsInspection */
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\EntityManager;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use FOS\MessageBundle\EntityManager\ThreadManager;
use FOS\MessageBundle\Model\AbstractThreadMetadata;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\Model\ThreadFactoryInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ThreadMetadataFactoryInterface;
use FOS\MessageBundle\Model\ThreadMetadataInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Mockery;
use Mockery\MockInterface;
use PDO;

/**
 * Default ORM ThreadManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ThreadManagerTest extends AbstractTestCase
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * The model class.
     *
     * @var string
     */
    protected $class;

    /**
     * The model class.
     *
     * @var string
     */
    protected $metaClass;

    /**
     * @var MessageManagerInterface|MockInterface
     */
    protected $messageManager;

    /**
     * @var ThreadMetadataFactoryInterface|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $threadMetadataFactory;
    /**
     * @var ThreadFactoryInterface|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $threadFactory;
    /**
     * @var ThreadManager
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em                    = Mockery::mock(EntityManagerInterface::class);
        $this->threadMetadataFactory = Mockery::mock(ThreadMetadataFactoryInterface::class);
        $this->messageManager        = Mockery::mock(MessageManagerInterface::class);
        $this->threadFactory         = Mockery::mock(ThreadFactoryInterface::class);
        $this->repository            = Mockery::mock(EntityRepository::class);

        $this->threadFactory->shouldReceive('getEntityClass')->once()->andReturn('some class');
        $this->em->shouldReceive('getRepository')
            ->once()
            ->with('some class')
            ->andReturn($this->repository);

        $this->service = new ThreadManager(
            $this->em,
            $this->threadFactory,
            $this->threadMetadataFactory,
            $this->messageManager
        );
    }

    /**
     * @test
     */
    public function findThreadById(): void
    {
        $id = 5;
        $entity = Mockery::mock(ThreadInterface::class);
        $this->repository->shouldReceive('find')->once()->with($id)->andReturn($entity);

        $actual = $this->service->findThreadById($id);

        self::assertSame($entity, $actual);
    }

    /**
     * @test
     */
    public function findParticipantInboxThreads(): void
    {
        $qb = Mockery::mock(QueryBuilder::class);
        $participant = Mockery::mock(ParticipantInterface::class);

        $id = 5;

        $participant->shouldReceive('getId')->once()->withNoArgs()->andReturn($id);

        $this->repository->shouldReceive('createQueryBuilder')
            ->once()
            ->with('t')
            ->andReturn($qb);

        $qb->shouldReceive('innerJoin')
            ->once()
            ->with('t.metadata', 'tm')
            ->andReturn($qb);

        $qb->shouldReceive('innerJoin')
            ->once()
            ->with('tm.participant', 'p')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('p.id = :user_id')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('t.isSpam = :isSpam')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('tm.isDeleted = :isDeleted')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('tm.lastMessageDate IS NOT NULL')
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('user_id', $id)
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('isSpam', false, PDO::PARAM_BOOL)
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('isDeleted', false, PDO::PARAM_BOOL)
            ->andReturn($qb);

        $qb->shouldReceive('orderBy')
            ->once()
            ->with('tm.lastMessageDate', 'DESC')
            ->andReturn($qb);

        $query = Mockery::mock(AbstractQuery::class);

        $qb->shouldReceive('getQuery')->once()->withNoArgs()->andReturn($query);

        $expected = [];

        $query->shouldReceive('execute')->once()->andReturn($expected);

        $actual = $this->service->findParticipantInboxThreads($participant);

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function findParticipantSentThreads(): void
    {
        $qb          = Mockery::mock(QueryBuilder::class);
        $participant = Mockery::mock(ParticipantInterface::class);

        $id = 5;

        $participant->shouldReceive('getId')->once()->withNoArgs()->andReturn($id);

        $this->repository->shouldReceive('createQueryBuilder')
            ->once()
            ->with('t')
            ->andReturn($qb);

        $qb->shouldReceive('innerJoin')
            ->once()
            ->with('t.metadata', 'tm')
            ->andReturn($qb);

        $qb->shouldReceive('innerJoin')
            ->once()
            ->with('tm.participant', 'p')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('p.id = :user_id')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('t.isSpam = :isSpam')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('tm.isDeleted = :isDeleted')
            ->andReturn($qb);

        $qb->shouldReceive('andWhere')
            ->once()
            ->with('tm.lastParticipantMessageDate IS NOT NULL')
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('user_id', $id)
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('isSpam', false, PDO::PARAM_BOOL)
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('isDeleted', false, PDO::PARAM_BOOL)
            ->andReturn($qb);

        $qb->shouldReceive('orderBy')
            ->once()
            ->with('tm.lastParticipantMessageDate', 'DESC')
            ->andReturn($qb);

        $query = Mockery::mock(AbstractQuery::class);

        $qb->shouldReceive('getQuery')->once()->withNoArgs()->andReturn($query);

        $expected = [];

        $query->shouldReceive('execute')->once()->andReturn($expected);

        $actual = $this->service->findParticipantSentThreads($participant);

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function findParticipantDeletedThreads(): void
    {
        $qb          = Mockery::mock(QueryBuilder::class);
        $participant = Mockery::mock(ParticipantInterface::class);

        $id = 5;

        $participant->shouldReceive('getId')->once()->withNoArgs()->andReturn($id);

        $this->repository->shouldReceive('createQueryBuilder')
            ->once()
            ->with('t')
            ->andReturn($qb);

        $qb->shouldReceive('innerJoin')
            ->once()
            ->with('t.metadata', 'tm')
            ->andReturn($qb);

        $qb->shouldReceive('innerJoin')
            ->once()
            ->with('tm.participant', 'p')
            ->andReturn($qb);


        $qb->shouldReceive('andWhere')
            ->once()
            ->with('p.id = :user_id')
            ->andReturn($qb);


        $qb->shouldReceive('andWhere')
            ->once()
            ->with('tm.isDeleted = :isDeleted')
            ->andReturn($qb);


        $qb->shouldReceive('setParameter')
            ->once()
            ->with('user_id', $id)
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('isDeleted', true, PDO::PARAM_BOOL)
            ->andReturn($qb);

        $qb->shouldReceive('orderBy')
            ->once()
            ->with('tm.lastMessageDate', 'DESC')
            ->andReturn($qb);

        $query = Mockery::mock(AbstractQuery::class);

        $qb->shouldReceive('getQuery')->once()->withNoArgs()->andReturn($query);

        $expected = [];

        $query->shouldReceive('execute')->once()->andReturn($expected);

        $actual = $this->service->findParticipantDeletedThreads($participant);

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function findThreadsCreatedBy(): void
    {
        $qb          = Mockery::mock(QueryBuilder::class);
        $participant = Mockery::mock(ParticipantInterface::class);

        $id = 5;

        $participant->shouldReceive('getId')->once()->withNoArgs()->andReturn($id);

        $this->repository->shouldReceive('createQueryBuilder')
            ->once()
            ->with('t')
            ->andReturn($qb);

        $qb->shouldReceive('innerJoin')
            ->once()
            ->with('t.createdBy', 'p')
            ->andReturn($qb);

        $qb->shouldReceive('where')
            ->once()
            ->with('p.id = :participant_id')
            ->andReturn($qb);

        $qb->shouldReceive('setParameter')
            ->once()
            ->with('participant_id', $id)
            ->andReturn($qb);

        $query = Mockery::mock(AbstractQuery::class);

        $qb->shouldReceive('getQuery')->once()->withNoArgs()->andReturn($query);

        $expected = [];

        $query->shouldReceive('execute')->once()->andReturn($expected);

        $actual = $this->service->findThreadsCreatedBy($participant);

        self::assertSame($expected, $actual);
    }


    /**
     * @test
     */
    public function saveThread(): void
    {
        $participant1 = \Mockery::mock(ParticipantInterface::class);
        $participant2 = \Mockery::mock(ParticipantInterface::class);

        $message1 = \Mockery::mock(MessageInterface::class);
        $sender1 = \Mockery::mock(ParticipantInterface::class);


        $thread = Mockery::mock(ThreadInterface::class);
        $thread->shouldReceive('getParticipants')
            ->once()
            ->andReturn(new ArrayCollection([$participant1, $participant2]));
        $thread->shouldReceive('getMessages')->times(4)->andReturn(new ArrayCollection([$message1, ]));

        $message1->shouldReceive('getSender')->andReturn($sender1);



        $thread->shouldReceive('getMetadataForParticipant')->once()->with($participant1)->andReturnNull();

        $metaForParticipant1 = Mockery::mock(AbstractThreadMetadata::class);
        $metaForParticipant1->shouldReceive('setParticipant')->once()->with($participant1);

        $metaForParticipant2 = Mockery::mock(AbstractThreadMetadata::class);


        $this->threadMetadataFactory->shouldReceive('create')->once()->andReturn($metaForParticipant1);

        $thread->shouldReceive('addMetadata')->once()->with($metaForParticipant1);

        $metaForSender1 = Mockery::mock(AbstractThreadMetadata::class);
        $metaForSender1->shouldReceive('setParticipant')->once()->with($sender1);

        $thread->shouldReceive('getMetadataForParticipant')
            ->once()
            ->with($participant2)
            ->andReturn($metaForParticipant2)
        ;

        $thread->shouldReceive('getMetadataForParticipant')->once()->with($sender1)->andReturnNull();

        $this->threadMetadataFactory->shouldReceive('create')->once()->andReturn($metaForSender1);

        $createdAt = \Mockery::mock(\DateTime::class);

        $message1->shouldReceive('getCreatedAt')->twice()->andReturn($createdAt);

        $metaForSender1->shouldReceive('setLastParticipantMessageDate')->once()->with($createdAt);
        $thread->shouldReceive('addMetadata')->once()->with($metaForSender1);

        $thread->shouldReceive('getFirstMessage')->once()->andReturn($message1);

        $thread->shouldReceive('getCreatedAt')->once()->andReturnNull();
        $thread->shouldReceive('setCreatedAt')->once()->with($createdAt);

        $thread->shouldReceive('getCreatedBy')->once()->andReturnNull();
        $thread->shouldReceive('setCreatedBy')->once()->with($sender1);

        $this->em->shouldReceive('persist')->once()->with($thread)->andReturnNull();
        $this->em->shouldReceive('flush')->once()->withNoArgs()->andReturnNull();

        $thread->shouldReceive('getAllMetadata')->once()->andReturn(
            new ArrayCollection(
                [
                    $metaForParticipant1,
                    $metaForParticipant2,
                    $metaForSender1
                ]
            )
        );

        $metaForSender1->shouldReceive('getParticipant')->once()->andReturn($sender1);
        $metaForParticipant1->shouldReceive('getParticipant')->once()->andReturn($participant1);
        $metaForParticipant2->shouldReceive('getParticipant')->once()->andReturn($participant2);

        $participant1->shouldReceive('getId')->times(1)->andReturn(100);
        $participant2->shouldReceive('getId')->times(1)->andReturn(200);
        $sender1->shouldReceive('getId')->times(4)->andReturn(300);

        $message1->shouldReceive('getTimestamp')->twice()->andReturn(12345);

        $dt = new DateTime();
        $dt->setTimestamp(12345);

        $compare = static function (DateTime $dateTime) use ($dt) {
            return $dt->getTimestamp() === $dateTime->getTimestamp();
        };

        $metaForParticipant1->shouldReceive('setLastMessageDate')->once()->with(\Mockery::on($compare));
        $metaForParticipant2->shouldReceive('setLastMessageDate')->once()->with(\Mockery::on($compare));

        self::assertNull($this->service->saveThread($thread));
    }

    /**
     * @test
     */
    public function deleteThread(): void
    {
        $thread = \Mockery::mock(ThreadInterface::class);

        $this->em->shouldReceive('remove')->with($thread)->once();
        $this->em->shouldReceive('flush')->once()->withNoArgs();

        self::assertNull($this->service->deleteThread($thread));
    }

    /**
     * @test
     */
    public function createThread(): void
    {
        $thread = \Mockery::mock(ThreadInterface::class);

        $this->threadFactory->shouldReceive('create')->once()->withNoArgs()->andReturn($thread);

        $actual = $this->service->createThread();

        self::assertSame($thread, $actual);
    }

    /**
     * @test
     */
    public function markAsReadByParticipant(): void
    {
        $readable    = \Mockery::mock(ReadableInterface::class);
        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->messageManager->shouldReceive('markIsReadByThreadAndParticipant')
            ->once()
            ->with($readable, $participant, true);

        self::assertNull($this->service->markAsReadByParticipant($readable, $participant));
    }

    /**
     * @test
     */
    public function markAsUnreadByParticipant(): void
    {
        $readable    = \Mockery::mock(ReadableInterface::class);
        $participant = \Mockery::mock(ParticipantInterface::class);

        $this->messageManager->shouldReceive('markIsReadByThreadAndParticipant')
            ->once()
            ->with($readable, $participant, false);

        self::assertNull($this->service->markAsUnreadByParticipant($readable, $participant));
    }
}
