<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\DataFixtures\Message;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use FOS\MessageBundle\DataFixtures\AbstractFixture;
use FOS\MessageBundle\DataFixtures\Participant\ParticipantFixture;
use FOS\MessageBundle\DataFixtures\Thread\ThreadFixture;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Entity\Thread;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Class MessageFixture
 * @package FOS\MessageBundle\DataFixtures\Message
 */
class MessageFixture extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $entity = new Message();

        /**
         * @var ParticipantInterface $participant
         */
        $participant = $this->getReference(ParticipantFixture::REFERENCE_PARTICIPANT_1);
        /**
         * @var Thread $thread
         */
        $thread = $this->getReference(ThreadFixture::class);

        $entity
            ->setSender($participant)
            ->setThread($thread)
            ->setIsModerated(true)
            ->setBody('Some body');

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
            ParticipantFixture::class,
            ThreadFixture::class,
        ];
    }
}
