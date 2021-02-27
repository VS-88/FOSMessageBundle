<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\MessageBundle\Tests\Functional\Entity\DummyParticipant;

/**
 * Class DummyParticipantRepository
 * @package FOS\MessageBundle\Repository
 */
class DummyParticipantRepository extends ServiceEntityRepository
{
    /**
     * MessageRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DummyParticipant::class);
    }
}
