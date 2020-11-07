<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\Functional\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_message_participant")
 * @ORM\Entity(repositoryClass="FOS\MessageBundle\Tests\Functional\Repository\DummyParticipantRepository")
 */
class DummyParticipant implements ParticipantInterface
{
    public const TABLE_NAME = 'fos_message_participant';

    public const TABLE_COLUMN_ID = 'id';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true}))
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
}
