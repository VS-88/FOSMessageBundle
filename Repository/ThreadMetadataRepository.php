<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\MessageBundle\Entity\ThreadMetadata;

/**
 * @method ThreadMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThreadMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThreadMetadata[]    findAll()
 * @method ThreadMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadMetadataRepository extends ServiceEntityRepository
{
    /**
     * MessageRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThreadMetadata::class);
    }
}
