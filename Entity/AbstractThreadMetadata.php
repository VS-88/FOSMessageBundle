<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Entity;

use DateTime;
use FOS\MessageBundle\Model;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractThreadMetadata
 * @package FOS\MessageBundle\Entity
 */
abstract class AbstractThreadMetadata extends Model\AbstractThreadMetadata
{
    /**
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $isDeleted = false;

    /**
     * @ORM\Column(name="last_message_date", type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $lastMessageDate;

    /**
     * @ORM\Column(name="last_participant_message_date", type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $lastParticipantMessageDate;
}
