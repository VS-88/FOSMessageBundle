<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\FormModel;

use FOS\MessageBundle\Model\ThreadInterface;

/**
 * Class ReplyMessage
 * @package FOS\MessageBundle\FormModel
 */
class ReplyMessage extends AbstractMessage
{
    /**
     * The thread we reply to.
     *
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * @return ThreadInterface
     */
    public function getThread(): ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @param ThreadInterface $thread
     *
     * @return AbstractMessage
     */
    public function setThread(ThreadInterface $thread): AbstractMessage
    {
        $this->thread = $thread;

        return  $this;
    }
}
