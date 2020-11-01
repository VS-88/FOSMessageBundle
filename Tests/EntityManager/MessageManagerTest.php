<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\EntityManager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use FOS\MessageBundle\EntityManager\MessageManager;
use FOS\MessageBundle\Model\MessageFactoryInterface;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\MessageMetadataFactoryInterface;
use FOS\MessageBundle\Model\MessageMetadataInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ThreadMetadataInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Mockery\MockInterface;
use PDO;

/**
 * Default ORM MessageManager.
 */
class MessageManagerTest extends AbstractTestCase
{
    /**
     * @var EntityManager|MockInterface
     */
    private $em;

    /**
     * @var EntityRepository|MockInterface
     */
    private $repository;

    /**
     * @var MessageFactoryInterface|MockInterface
     */
    private $messageFactory;

    /**
     * @var MessageMetadataFactoryInterface|MockInterface
     */
    private $messageMetadataFactory;

    /**
     * @var MessageManager
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em                     = \Mockery::mock(EntityManager::class);
        $this->messageFactory         = \Mockery::mock(MessageFactoryInterface::class);
        $this->messageMetadataFactory = \Mockery::mock(MessageMetadataFactoryInterface::class);

        $this->messageFactory->shouldReceive('getEntityClass')->once()->andReturn('SomeEntityClass');


        $this->repository = \Mockery::mock(EntityRepository::class);

        $this->em->shouldReceive('getRepository')->once()->andReturn($this->repository);

        $this->service = new MessageManager(
            $this->em,
            $this->messageFactory,
            $this->messageMetadataFactory
        );
    }


    /**
     * @test
     */
    public function getNbUnreadMessageByParticipant(): void
    {
        $participantId = 100500;

        $participant = \Mockery::mock(ParticipantInterface::class);
        $participant->shouldReceive('getId')->once()->andReturn($participantId);

        $builder = \Mockery::mock(QueryBuilder::class);

        $this->repository->shouldReceive('createQueryBuilder')->once()
            ->with('m')
            ->andReturn($builder);

        $expr = \Mockery::mock(Expr::class);

        $func = \Mockery::mock(Expr\Func::class);

        $expr->shouldReceive('count')->once()->with('mm.id')->andReturn($func);

        $builder->shouldReceive('select')->once()
            ->with($func)
            ->andReturn($builder);

        $builder->shouldReceive('expr')->once()
            ->with()
            ->andReturn($expr);

        $builder->shouldReceive('innerJoin')->once()
            ->with('m.metadata', 'mm')
            ->andReturn($builder);

        $builder->shouldReceive('innerJoin')->once()
            ->with('mm.participant', 'p')
            ->andReturn($builder);

        $builder->shouldReceive('where')->once()
            ->with('p.id = :participant_id')
            ->andReturn($builder);

        $builder->shouldReceive('setParameter')->once()
            ->with('participant_id', $participantId)
            ->andReturn($builder);

        $builder->shouldReceive('andWhere')->once()
            ->with('m.sender != :sender')
            ->andReturn($builder);

        $builder->shouldReceive('setParameter')->once()
            ->with('sender', $participantId)
            ->andReturn($builder);

        $builder->shouldReceive('andWhere')->once()
            ->with('mm.isRead = :isRead')
            ->andReturn($builder);

        $builder->shouldReceive('setParameter')->once()
            ->with('isRead', false, PDO::PARAM_BOOL)
            ->andReturn($builder);

        $query = \Mockery::mock(AbstractQuery::class);

        $builder->shouldReceive('getQuery')->once()
            ->withNoArgs()
            ->andReturn($query);

        $query->shouldReceive('getSingleScalarResult')->once()->withNoArgs()->andReturn('2');

        $actual = $this->service->getNbUnreadMessageByParticipant($participant);

        self::assertSame(2, $actual);
    }

    /**
     * @test
     */
    public function markAsReadByParticipant(): void
    {
        $readable = \Mockery::mock(ReadableInterface::class);
        $participant = \Mockery::mock(ParticipantInterface::class);

        $readable->shouldReceive('setIsReadByParticipant')->once()->with($participant, true);

        self::assertNull($this->service->markAsReadByParticipant($readable, $participant));
    }

    /**
     * @test
     */
    public function markAsUnreadByParticipant(): void
    {
        $readable = \Mockery::mock(ReadableInterface::class);
        $participant = \Mockery::mock(ParticipantInterface::class);

        $readable->shouldReceive('setIsReadByParticipant')->once()->with($participant, false);

        self::assertNull($this->service->markAsUnreadByParticipant($readable, $participant));
    }

    /**
     * @test
     */
    public function markIsReadByThreadAndParticipant(): void
    {
        $this->expectNotToPerformAssertions();
        $isRead = true;

        $thread      = \Mockery::mock(ThreadInterface::class);
        $participant = \Mockery::mock(ParticipantInterface::class);
        $m = \Mockery::mock(MessageInterface::class);

        $messages = [
            $m
        ];

        $metaData = \Mockery::mock(MessageMetadataInterface::class);

        $m->shouldReceive('getMetadataForParticipant')
            ->once()
            ->with($participant)
            ->andReturn($metaData);

        $thread->shouldReceive('getMessages')->once()->withNoArgs()->andReturn(
            new ArrayCollection($messages)
        );

        $this->em->shouldReceive('beginTransaction')->once()->withNoArgs();

        $metaData->shouldReceive(
            'setIsRead'
        )->once()->with($isRead);

        $this->em->shouldReceive('persist')->once()->with($metaData)->andReturnNull();
        $this->em->shouldReceive('flush')->once()->withNoArgs()->andReturnNull();
        $this->em->shouldReceive('commit')->once()->withNoArgs()->andReturnNull();
        $this->em->shouldReceive('clear')->once()->withNoArgs()->andReturnNull();

        $this->service->markIsReadByThreadAndParticipant($thread, $participant, $isRead);
    }

    /**
     * @test
     */
    public function saveMessage(): void
    {
        $this->expectNotToPerformAssertions();

        $message = \Mockery::mock(MessageInterface::class);
        $thread  = \Mockery::mock(ThreadInterface::class);
        $participant = \Mockery::mock(ParticipantInterface::class);

        $message->shouldReceive('getMetadataForParticipant')
            ->once()
            ->with($participant)
            ->andReturnNull();

        $message->shouldReceive('getThread')->once()->andReturn($thread);

        $meta = \Mockery::mock(ThreadMetadataInterface::class);
        $meta->shouldReceive('getParticipant')->once()->andReturn($participant);

        $thread->shouldReceive('getAllMetadata')->once()->andReturn(new ArrayCollection([$meta]));

        $messageMeta = \Mockery::mock(MessageMetadataInterface::class);

        $this->messageMetadataFactory->shouldReceive('create')->once()->andReturn($messageMeta);

        $messageMeta->shouldReceive('setParticipant')->once()->with($participant);

        $message->shouldReceive('addMetadata')->once()->with($messageMeta);

        $this->em->shouldReceive('persist')->once()->with($message);
        $this->em->shouldReceive('flush')->once()->withNoArgs();

        $this->service->saveMessage($message);
    }
}
