<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Functional\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package FOS\MessageBundle\Tests\Functional\Entity
 */
class User extends DummyParticipant
{
    /**
     * @return string
     */
    public function getUsername(): string
    {
        return 'guilhem';
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return 'pass';
    }
}
