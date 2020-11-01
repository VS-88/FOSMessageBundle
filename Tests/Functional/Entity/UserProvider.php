<?php
declare(strict_types = 1);
namespace FOS\MessageBundle\Tests\Functional\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 * @package FOS\MessageBundle\Tests\Functional\Entity
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @param string $username
     * @return User|UserInterface
     */
    public function loadUserByUsername($username)
    {
        return new User();
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
