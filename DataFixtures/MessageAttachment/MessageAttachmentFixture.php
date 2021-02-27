<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\DataFixtures\MessageAttachment;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use FOS\MessageBundle\DataFixtures\AbstractFixture;
use FOS\MessageBundle\DataFixtures\Message\MessageFixture;
use FOS\MessageBundle\Entity\Message;
use FOS\MessageBundle\Entity\MessageAttachment;

/**
 * Class MessageAttachmentFixture
 * @package FOS\MessageBundle\DataFixtures\MessageAttachment
 */
class MessageAttachmentFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const REFERENCE_MESSAGE_ATTACHMENT_1 = 'message_attachment_1';

    public const REFERENCE_MESSAGE_ATTACHMENT_1_FILE_NAME = 'message_attachment_file_name_1';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $path = self::REFERENCE_MESSAGE_ATTACHMENT_1_FILE_NAME;

        /**
         * @var Message $message
         */
        $message = $this->getReference(MessageFixture::class);

        $entity1 = new MessageAttachment();
        $entity1
            ->setFileName($path)
            ->setMessage($message);

        $manager->persist($entity1);
        $manager->flush();

        $this->addReference(self::REFERENCE_MESSAGE_ATTACHMENT_1, $entity1);
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
            MessageFixture::class,
        ];
    }
}
