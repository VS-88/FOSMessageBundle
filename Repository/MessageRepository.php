<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Provider\ModerationAwareMessageProviderInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository implements ModerationAwareMessageProviderInterface
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
     * @return AbstractQuery
     */
    public function getMessagesByModerationFlag(bool $isModerated): AbstractQuery
    {
        $dql   = "SELECT m FROM FOS\MessageBundle\Entity\Message m
                    WHERE m.isModerated = :flag
                  ORDER BY m.id ASC" ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('flag', $isModerated);

        return $query;
    }
}
