<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery as AQ;
use Doctrine\Persistence\ManagerRegistry;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Provider\MessagesInboxProviderInterface;
use FOS\MessageBundle\Provider\MessagesSentProviderInterface;
use FOS\MessageBundle\Provider\ModerationAwareMessageProviderInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository implements ModerationAwareMessageProviderInterface,
    MessagesInboxProviderInterface,
    MessagesSentProviderInterface
{
    /**
     * MessageRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @param bool $isModerated
     * @return AQ
     */
    public function getMessagesByModerationFlag(bool $isModerated): AQ
    {
        $dql   = "SELECT m FROM FOS\MessageBundle\Entity\Message m
                    WHERE m.isModerated = :flag
                  ORDER BY m.id ASC" ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('flag', $isModerated);

        return $query;
    }

    /**
     * @param ParticipantInterface $participant
     * @return AQ
     */
    public function getMessagesByParticipantOrderByDateAndIsReadStatus(ParticipantInterface $participant): AQ
    {
        $dql   = "SELECT m FROM FOS\MessageBundle\Entity\Message m
                    JOIN FOS\MessageBundle\Entity\MessageMetadata mm
                    WHERE m.isModerated = 1 
                    AND m.sender != :participant_id
                    AND mm.participant = :participant_id
                  ORDER BY m.createdAt DESC, mm.isRead DESC" ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('participant_id', $participant->getId());

        return $query;
    }

    /**
     * @param ParticipantInterface $participant
     * @return AQ
     */
    public function getSentMessagesByParticipantOrderByDate(ParticipantInterface $participant): AQ
    {
        $dql   = "SELECT m FROM FOS\MessageBundle\Entity\Message m
                    WHERE m.sender = :participant_id
                  ORDER BY m.createdAt DESC" ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('participant_id', $participant->getId());

        return $query;
    }
}
