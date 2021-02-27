<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\DataFixtures\Thread;

use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use FOS\MessageBundle\DataFixtures\AbstractFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\Entity\Thread;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Class ThreadFixture
 * @package FOS\MessageBundle\DataFixtures\Thread
 */
class ThreadFixture extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $entity = new Thread();

        /**
         * @var ParticipantInterface $participant
         */
        $participant = $this->getReference(ParticipantFixture::REFERENCE_PARTICIPANT_1);

        $entity
            ->setCreatedBy($participant)
            ->setCreatedAt(new DateTime())
            ->setIsSpam(false)
            ->setIsDeleted(false)
            ->setSubject('test');

        $manager->persist($entity);
        $manager->flush();

        $this->addReference(static::class, $entity);
    }


    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return [
            static::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            ParticipantFixture::class
        ];
    }
}
