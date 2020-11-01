<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional\EntityManager;

use Doctrine\ORM\AbstractQuery;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\ModelManager\MessageManager as BaseMessageManager;
use FOS\MessageBundle\Provider\ModerationAwareMessageProviderInterface;
use FOS\MessageBundle\Tests\Functional\Entity\Message;

/**
 * Class MessageManager
 * @package FOS\MessageBundle\Tests\Functional\EntityManager
 */
class MessageManager extends BaseMessageManager implements ModerationAwareMessageProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getNbUnreadMessageByParticipant(ParticipantInterface $participant): int
    {
        return 3;
    }

    /**
     * {@inheritDoc}
     */
    public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
        /**
         * NOP
         */
    }

    /**
     * {@inheritDoc}
     */
    public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $participant): void
    {
        /**
         * NOP
         */
    }

    /**
     * {@inheritDoc}
     */
    public function saveMessage(MessageInterface $message, $andFlush = true): void
    {
        /**
         * NOP
         */
    }

    public function createMessage(): MessageInterface
    {
        return new Message();
    }

    public function getMessagesByModerationFlag(bool $isModerated): AbstractQuery
    {
        // TODO: Implement getMessagesByModerationFlag() method.
    }
}
