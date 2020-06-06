<?php
declare(strict_types=1);

namespace FOS\MessageBundle\ModelManager;

use Exception;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Interface to be implemented by message managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to messages should happen through this interface.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface MessageManagerInterface extends ReadableManagerInterface
{
    /**
     * Tells how many unread, non-spam, messages this participant has.
     *
     * @param ParticipantInterface $participant
     *
     * @return int the number of unread messages
     *
     * @throws Exception
     */
    public function getNbUnreadMessageByParticipant(ParticipantInterface $participant): int;

    /**
     * Creates an empty message instance.
     *
     * @return MessageInterface
     */
    public function createMessage(): MessageInterface;

    /**
     * Saves a message.
     *
     * @param MessageInterface $message
     * @param bool             $andFlush Whether to flush the changes (default true)
     *
     * @throws \Exception
     */
    public function saveMessage(MessageInterface $message, bool $andFlush = true): void;
}
