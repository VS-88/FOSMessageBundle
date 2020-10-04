<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Provider;

use Doctrine\ORM\AbstractQuery;

/**
 * Interface ModerationAwareMessageProviderInterface
 * @package FOS\MessageBundle\Provider
 */
interface ModerationAwareMessageProviderInterface
{
    /**
     * @param bool $isModerated
     * @return AbstractQuery
     */
    public function getMessagesByModerationFlag(bool $isModerated): AbstractQuery;
}
