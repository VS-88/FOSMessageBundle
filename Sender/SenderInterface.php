<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Sender;

use Exception;
use FOS\MessageBundle\Model\MessageInterface;

/**
 * Sends messages.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface SenderInterface
{
    /**
     * Sends the given message.
     * @param MessageInterface $message
     *
     * @return  void
     *
     * @throws Exception
     */
    public function send(MessageInterface $message): void;
}
