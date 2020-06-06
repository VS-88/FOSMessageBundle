<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\EntityManager;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\Model\ThreadFactoryInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ThreadMetadataFactoryInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManager as BaseThreadManager;
use PDO;
use RuntimeException;

/**
 * Default ORM ThreadManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ThreadManager extends BaseThreadManager
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
    protected $metaClass;

    /**
     * @var ThreadFactoryInterface
     */
    private $threadFactory;

    /**
     * The message manager, required to mark
     * the messages of a thread as read/unread.
     *
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * @var ThreadMetadataFactoryInterface
     */
    private $threadMetadataFactory;

    /**
     * @param EntityManagerInterface $em
     * @param ThreadFactoryInterface $threadFactory
     * @param ThreadMetadataFactoryInterface $threadMetadataFactory
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        EntityManagerInterface $em,
        ThreadFactoryInterface $threadFactory,
        ThreadMetadataFactoryInterface $threadMetadataFactory,
        MessageManagerInterface $messageManager
    ) {
        $this->em                    = $em;
        $this->threadMetadataFactory = $threadMetadataFactory;
        $this->messageManager        = $messageManager;
        $this->threadFactory         = $threadFactory;
        $this->repository            = $em->getRepository($threadFactory->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadById($id): ?ThreadInterface
    {
        /**
         * @var ThreadInterface|null $thread
         */
        $thread = $this->repository->find($id);
        return $thread;
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipantInboxThreadsQueryBuilder(ParticipantInterface $participant): QueryBuilder
    {
        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, PDO::PARAM_BOOL)

            // there is at least one message written by an other participant
            ->andWhere('tm.lastMessageDate IS NOT NULL')

            // sort by date of last message written by an other participant
            ->orderBy('tm.lastMessageDate', 'DESC')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findParticipantInboxThreads(ParticipantInterface $participant): array
    {
        return $this->getParticipantInboxThreadsQueryBuilder($participant)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipantSentThreadsQueryBuilder(ParticipantInterface $participant): QueryBuilder
    {
        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, PDO::PARAM_BOOL)

            // there is at least one message written by this participant
            ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')

            // sort by date of last message written by this participant
            ->orderBy('tm.lastParticipantMessageDate', 'DESC')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findParticipantSentThreads(ParticipantInterface $participant): array
    {
        return $this->getParticipantSentThreadsQueryBuilder($participant)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipantDeletedThreadsQueryBuilder(ParticipantInterface $participant): QueryBuilder
    {
        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread is deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', true, PDO::PARAM_BOOL)

            // sort by date of last message
            ->orderBy('tm.lastMessageDate', 'DESC')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findParticipantDeletedThreads(ParticipantInterface $participant): array
    {
        return $this->getParticipantDeletedThreadsQueryBuilder($participant)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipantThreadsBySearchQueryBuilder(ParticipantInterface $participant, $search): QueryBuilder
    {
        // remove all non-word chars
        $search = preg_replace('/[^\w]/', ' ', trim($search));
        // build a regex like (term1|term2)
        $regex = sprintf('/(%s)/', implode('|', explode(' ', $search)));

        throw new RuntimeException('not yet implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function findParticipantThreadsBySearch(ParticipantInterface $participant, $search): array
    {
        return $this->getParticipantThreadsBySearchQueryBuilder($participant, $search)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadsCreatedBy(ParticipantInterface $participant): array
    {
        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.createdBy', 'p')

            ->where('p.id = :participant_id')
            ->setParameter('participant_id', $participant->getId())

            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
        /**
         * @var ThreadInterface $readable
         */
        $this->messageManager->markIsReadByThreadAndParticipant($readable, $participant, true);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
        /**
         * @var ThreadInterface $readable
         */
        $this->messageManager->markIsReadByThreadAndParticipant($readable, $participant, false);
    }

    /**
     * {@inheritdoc}
     */
    public function saveThread(ThreadInterface $thread, $andFlush = true): void
    {
        $this->denormalize($thread);
        $this->em->persist($thread);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteThread(ThreadInterface $thread): void
    {
        $this->em->remove($thread);
        $this->em->flush();
    }

    /*
     * DENORMALIZATION
     *
     * All following methods are relative to denormalization
     */

    /**
     * Performs denormalization tricks.
     * @param ThreadInterface $thread
     */
    private function denormalize(ThreadInterface $thread): void
    {
        $this->doMetadata($thread);
        $this->doCreatedByAndAt($thread);
        $this->doDatesOfLastMessageWrittenByOtherParticipant($thread);
    }

    /**
     * Ensures that the thread metadata are up to date.
     * @param ThreadInterface $thread
     */
    private function doMetadata(ThreadInterface $thread): void
    {
        // Participants
        foreach ($thread->getParticipants() as $participant) {
            $meta = $thread->getMetadataForParticipant($participant);
            if (!$meta) {
                $meta = $this->threadMetadataFactory->create();
                $meta->setParticipant($participant);

                $thread->addMetadata($meta);
            }
        }

        // Messages
        foreach ($thread->getMessages() as $message) {
            $meta = $thread->getMetadataForParticipant($message->getSender());
            if (!$meta) {
                $meta = $this->threadMetadataFactory->create();
                $meta->setParticipant($message->getSender());
                $thread->addMetadata($meta);
            }

            $meta->setLastParticipantMessageDate($message->getCreatedAt());
        }
    }

    /**
     * Ensures that the createdBy & createdAt properties are set.
     *
     * @param ThreadInterface $thread
     */
    private function doCreatedByAndAt(ThreadInterface $thread): void
    {
        if (!($message = $thread->getFirstMessage())) {
            return;
        }

        if (!$thread->getCreatedAt()) {
            $thread->setCreatedAt($message->getCreatedAt());
        }

        if (!$thread->getCreatedBy()) {
            $thread->setCreatedBy($message->getSender());
        }
    }

    /**
     * Update the dates of last message written by other participants.
     * @param ThreadInterface $thread
     */
    private function doDatesOfLastMessageWrittenByOtherParticipant(ThreadInterface $thread): void
    {
        foreach ($thread->getAllMetadata() as $i => $meta) {
            $participantId = $meta->getParticipant()->getId();
            $timestamp = 0;

            foreach ($thread->getMessages() as $message) {
                if ($participantId !== $message->getSender()->getId()) {
                    $timestamp = max($timestamp, $message->getTimestamp());
                }
            }

            if ($timestamp) {
                $date =  new DateTime();
                $date->setTimestamp($timestamp);
                $meta->setLastMessageDate($date);
            }
        }
    }

    /**
     * @return ThreadInterface
     */
    public function createThread(): ThreadInterface
    {
        return $this->threadFactory->create();
    }
}
