<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Functional\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package FOS\MessageBundle\Tests\Functional\Entity
 */
class User extends DummyParticipant implements UserInterface
{
    /**
     * @return string
     */
    public function getUsername()
    {
        return 'guilhem';
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return 'pass';
    }

    /**
     * @return string|void|null
     */
    public function getSalt()
    {
    }

    /**
     * @return array|string[]
     */
    public function getRoles()
    {
        return [];
    }

    public function eraseCredentials()
    {
    }
}
