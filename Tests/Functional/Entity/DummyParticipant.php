<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Functional\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="fos_message_participant")
 * @ORM\Entity(repositoryClass="FOS\MessageBundle\Tests\Functional\Repository\DummyParticipantRepository")
 */
class DummyParticipant implements ParticipantInterface, UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=254, unique=true, nullable=false)
     */
    private $email;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json", nullable=false)
     */
    private $roles = [];

    /**
     * @var string|null The hashed password
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return DummyParticipant
     */
    public function setEmail(?string $email): DummyParticipant
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return DummyParticipant
     */
    public function setRoles(array $roles): DummyParticipant
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return DummyParticipant
     */
    public function setPassword(?string $password): DummyParticipant
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        // NOP
    }
}
