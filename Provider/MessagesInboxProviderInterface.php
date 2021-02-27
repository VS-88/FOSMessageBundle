<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Provider;

use Doctrine\ORM\AbstractQuery;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Interface MessagesProviderInterface
 * @package FOS\MessageBundle\Provider
 */
interface MessagesInboxProviderInterface
{
    /**
     * @param ParticipantInterface $participant
     * @return AbstractQuery
     */
    public function getMessagesByParticipantOrderByDateAndIsReadStatus(
        ParticipantInterface $participant
    ): AbstractQuery;
}
