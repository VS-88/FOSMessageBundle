<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\DataFixtures\MessageMetadata;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use FOS\MessageBundle\DataFixtures\AbstractFixture;
use FOS\MessageBundle\DataFixtures\Message\MessageFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Entity\MessageMetadata;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Class MessageMetadataFixture
 * @package FOS\MessageBundle\DataFixtures\MessageMetadata
 */
class MessageMetadataFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const REFERENCE_MESSAGE_META_DATA_FOR_PARTICIPANT_1 = 'message_meta_data_for_participant_1';
    public const REFERENCE_MESSAGE_META_DATA_FOR_PARTICIPANT_2 = 'message_meta_data_for_participant_2';

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
         * @var Message $message
         */
        $message = $this->getReference(MessageFixture::class);

        $entity1 = new MessageMetadata();
        $entity1
            ->setParticipant($participant1)
            ->setMessage($message)
            ->setIsRead(true);


        $entity2 = new MessageMetadata();
        $entity2
            ->setParticipant($participant2)
            ->setMessage($message)
            ->setIsRead(false);

        $manager->persist($entity1);
        $manager->persist($entity2);
        $manager->flush();

        $this->addReference(self::REFERENCE_MESSAGE_META_DATA_FOR_PARTICIPANT_1, $entity1);
        $this->addReference(self::REFERENCE_MESSAGE_META_DATA_FOR_PARTICIPANT_2, $entity2);
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
            MessageFixture::class,
        ];
    }
}
