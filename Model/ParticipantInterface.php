<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A user participating to a thread.
 * May be implemented by a FOS\UserBundle user document or entity.
 * Or anything you use to represent users in the application.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface ParticipantInterface extends UserInterface
{
    /**
     * Gets the unique identifier of the participant.
     *
     * @return int|string
     */
    public function getId();

    /**
     * @return bool
     */
    public function isAdmin(): bool;
}
