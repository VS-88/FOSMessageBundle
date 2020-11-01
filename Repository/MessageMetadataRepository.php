<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\MessageBundle\Entity\MessageMetadata;

/**
 * @method MessageMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageMetadata[]    findAll()
 * @method MessageMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageMetadataRepository extends ServiceEntityRepository
{
    /**
     * MessageRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageMetadata::class);
    }
}
