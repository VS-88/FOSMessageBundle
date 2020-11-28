<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Functional\Form;

use FOS\MessageBundle\Tests\Functional\Entity\User;
use FOS\MessageBundle\Tests\Functional\Repository\DummyParticipantRepository;
use RuntimeException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserToUsernameTransformer
 * @package FOS\MessageBundle\Tests\Functional\Form
 */
class UserToUsernameTransformer implements DataTransformerInterface
{
    /**
     * @var DummyParticipantRepository
     */
    private $repo;

    /**
     * UserToUsernameTransformer constructor.
     * @param DummyParticipantRepository $repository
     */
    public function __construct(DummyParticipantRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param mixed $value
     * @return mixed|string|void
     */
    public function transform($value)
    {
        if (null === $value) {
            return;
        }

        if (!$value instanceof User) {
            throw new RuntimeException();
        }

        return $value->getUsername();
    }

    /**
     * Transforms a username string into a UserInterface instance.
     *
     * @param string $value Username
     *
     * @throws UnexpectedTypeException if the given value is not a string
     *
     * @return UserInterface the corresponding UserInterface instance
     */
    public function reverseTransform($value)
    {
        return $this->repo->findOneBy(['email' => $value]);
    }
}
