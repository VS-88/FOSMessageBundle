<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\DataFixtures\ThreadMetadata;

use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use FOS\MessageBundle\DataFixtures\AbstractFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\DataFixtures\Thread\ThreadFixture;
use FOS\MessageBundle\Entity\Thread;
use FOS\MessageBundle\Entity\ThreadMetadata;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Class ThreadFixture
 * @package FOS\MessageBundle\DataFixtures\Thread
 */
class ThreadMetadataFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const REFERENCE_THREAD_META_DATA_FOR_PARTICIPANT_1 = 'thread_meta_data_for_participant_1';
    public const REFERENCE_THREAD_META_DATA_FOR_PARTICIPANT_2 = 'thread_meta_data_for_participant_2';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        /**
         * @var ParticipantInterface $participant1
         */
        $participant1 = $this->getReference(ParticipantFixture::REFERENCE_PARTICIPANT_1);
        /**
         * @var ParticipantInterface $participant2
         */
        $participant2 = $this->getReference(ParticipantFixture::REFERENCE_PARTICIPANT_2);
        /**
         * @var Thread $thread
         */
        $thread = $this->getReference(ThreadFixture::class);

        $entity1 = new ThreadMetadata();
        $entity2 = new ThreadMetadata();

        $dt = new DateTime();

        $entity1
            ->setParticipant($participant1)
            ->setThread($thread)
            ->setIsDeleted(false)
            ->setLastMessageDate($dt)
            ->setLastParticipantMessageDate($dt);

        $entity2
            ->setParticipant($participant2)
            ->setThread($thread)
            ->setIsDeleted(false)
            ->setLastMessageDate($dt);

        $this->addReference(self::REFERENCE_THREAD_META_DATA_FOR_PARTICIPANT_1, $entity1);
        $this->addReference(self::REFERENCE_THREAD_META_DATA_FOR_PARTICIPANT_2, $entity2);
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
            ParticipantFixture::class,
            ThreadFixture::class,
        ];
    }
}
