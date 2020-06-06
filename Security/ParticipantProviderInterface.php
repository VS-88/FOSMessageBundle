<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Security;

use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Provides the authenticated participant.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface ParticipantProviderInterface
{
    /**
     * Gets the current authenticated user.
     *
     * @return ParticipantInterface
     *
     * @throws AccessDeniedException
     */
    public function getAuthenticatedParticipant(): ParticipantInterface;
}
