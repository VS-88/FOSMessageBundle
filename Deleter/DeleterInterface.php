<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Deleter;

use FOS\MessageBundle\Model\ThreadInterface;

/**
 * Marks threads as deleted.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface DeleterInterface
{
    /**
     * Marks the thread as deleted by the current authenticated user.
     * @param ThreadInterface $thread
     */
    public function markAsDeleted(ThreadInterface $thread): void;

    /**
     * Marks the thread as undeleted by the current authenticated user.
     * @param ThreadInterface $thread
     */
    public function markAsUndeleted(ThreadInterface $thread): void;
}
