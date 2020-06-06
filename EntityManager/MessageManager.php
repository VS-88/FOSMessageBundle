<?php
declare(strict_types=1);

namespace FOS\MessageBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use FOS\MessageBundle\Model\MessageFactoryInterface;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\MessageMetadataFactoryInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\MessageManager as BaseMessageManager;
use PDO;
use RuntimeException;

/**
 * Default ORM MessageManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class MessageManager extends BaseMessageManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var MessageFactoryInterface
     */
    private $messageFactory;

    /**
     * @var MessageMetadataFactoryInterface
     */
    private $messageMetadataFactory;

    /**
     * @param EntityManager $em
     * @param MessageFactoryInterface $messageFactory
     * @param MessageMetadataFactoryInterface $messageMetadataFactory
     */
    public function __construct(
        EntityManager $em,
        MessageFactoryInterface $messageFactory,
        MessageMetadataFactoryInterface $messageMetadataFactory
    ) {
        $this->em = $em;

        $this->messageFactory         = $messageFactory;
        $this->messageMetadataFactory = $messageMetadataFactory;

        $this->repository = $em->getRepository($messageFactory->getEntityClass());
    }

    /**
     * {@inheritdoc}
     */
    public function getNbUnreadMessageByParticipant(ParticipantInterface $participant): int
    {
        $builder = $this->repository->createQueryBuilder('m');

        $id = $participant->getId();

        return (int) $builder
            ->select($builder->expr()->count('mm.id'))

            ->innerJoin('m.metadata', 'mm')
            ->innerJoin('mm.participant', 'p')

            ->where('p.id = :participant_id')
            ->setParameter('participant_id', $id)

            ->andWhere('m.sender != :sender')
            ->setParameter('sender', $id)

            ->andWhere('mm.isRead = :isRead')
            ->setParameter('isRead', false, PDO::PARAM_BOOL)

            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
        $readable->setIsReadByParticipant($participant, true);
    }

    /**
     * {@inheritdoc}
     */
    public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
        $readable->setIsReadByParticipant($participant, false);
    }

    /**
     * Marks all messages of this thread as read by this participant.
     *
     * @param ThreadInterface      $thread
     * @param ParticipantInterface $participant
     * @param bool                 $isRead
     *
     * @return void
     *
     * @throws Exception
     */
    public function markIsReadByThreadAndParticipant(
        ThreadInterface $thread,
        ParticipantInterface $participant,
        bool $isRead
    ): void {
        $this->em->beginTransaction();
        
        try {
            $messages = $thread->getMessages();
            
            if (empty($messages) === false) {
                foreach ($messages as $message) {
                    $meta = $message->getMetadataForParticipant($participant);
                    
                    if ($meta !== null) {
                        $meta->setIsRead($isRead);
                        $this->em->persist($meta);
                    }
                }

                $this->em->flush();
                $this->em->commit();
            }
        } catch (RuntimeException $e) {
            $this->em->rollback();
            
            throw $e;
        }
        
        $this->em->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function saveMessage(MessageInterface $message, bool $andFlush = true): void
    {
        $this->denormalize($message);
        $this->em->persist($message);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * DENORMALIZATION
     *
     * All following methods are relative to denormalization
     *
     * Performs denormalization tricks.
     * @param MessageInterface $message
     */
    private function denormalize(MessageInterface $message): void
    {
        foreach ($message->getThread()->getAllMetadata() as $threadMeta) {
            $p    = $threadMeta->getParticipant();
            $meta = $message->getMetadataForParticipant($p);
            if (!$meta) {
                $meta =  $this->messageMetadataFactory->create();
                $meta->setParticipant($p);

                $message->addMetadata($meta);
            }
        }
    }

    /**
     * @return MessageInterface
     */
    public function createMessage(): MessageInterface
    {
        return $this->messageFactory->create();
    }
}
