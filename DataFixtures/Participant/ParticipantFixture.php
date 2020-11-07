<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\DataFixtures\Participant;

use Doctrine\Persistence\ObjectManager;
use FOS\MessageBundle\DataFixtures\AbstractFixture;
use FOS\MessageBundle\Tests\Functional\Entity\DummyParticipant;

/**
 * Class ParticipantFixture
 * @package FOS\MessageBundle\DataFixtures\Participant
 */
class ParticipantFixture extends AbstractFixture
{
    public const REFERENCE_PARTICIPANT_1 = 'participant_1';
    public const REFERENCE_PARTICIPANT_2 = 'participant_2';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach ([self::REFERENCE_PARTICIPANT_1, self::REFERENCE_PARTICIPANT_2] as $ref) {
            $entity = new DummyParticipant();

            $manager->persist($entity);
            $manager->flush();

            $this->addReference($ref, $entity);
        }
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
}
