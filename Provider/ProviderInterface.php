<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Provider;

use FOS\MessageBundle\Model\ThreadInterface;

/**
 * Provides threads for the current authenticated user.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface ProviderInterface
{
    /**
     * Gets the thread in the inbox of the current user.
     *
     * @return ThreadInterface[]
     */
    public function getInboxThreads(): array;

    /**
     * Gets the thread in the sentbox of the current user.
     *
     * @return ThreadInterface[]
     */
    public function getSentThreads(): array;

    /**
     * Gets the deleted threads of the current user.
     *
     * @return ThreadInterface[]
     */
    public function getDeletedThreads(): array;

    /**
     * Gets a thread by its ID
     * Performs authorization checks
     * Marks the thread as read.
     *
     * @param $threadId
     * @return ThreadInterface
     */
    public function getThreadAndMarkAsRead($threadId): ?ThreadInterface;

    /**
     * Tells how many unread messages the authenticated participant has.
     *
     * @return int the number of unread messages
     */
    public function getNbUnreadMessages(): int;

    /**
     * @param int $id
     * @return ThreadInterface
     */
    public function findThreadById(int $id): ThreadInterface;
}
