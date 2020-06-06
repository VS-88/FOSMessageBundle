<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Model\ThreadInterface;

/**
 * Class ThreadDeleteController
 */
class ThreadUndeleteController extends ThreadDeleteController
{
    /**
     * @param ThreadInterface $thread
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function processThread(ThreadInterface $thread): void
    {
        $this->deleter->markAsUndeleted($thread);
    }
}
