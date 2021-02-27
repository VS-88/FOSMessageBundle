<?php
declare(strict_types = 1);
namespace FOS\MessageBundle\Tests\Functional\Entity;

use FOS\MessageBundle\Tests\Functional\Repository\DummyParticipantRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 * @package FOS\MessageBundle\Tests\Functional\Entity
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var DummyParticipantRepository
     */
    private $repo;

    /**
     * UserProvider constructor.
     * @param DummyParticipantRepository $repository
     */
    public function __construct(DummyParticipantRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param string $username
     * @return User|UserInterface
     */
    public function loadUserByUsername($username)
    {
        return $this->repo->findOneBy(['email' => $username]);
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
        return User::class === $class || DummyParticipant::class === $class;
    }
}
