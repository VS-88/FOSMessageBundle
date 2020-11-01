<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\AbstractThreadMetadata;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_message_thread_metadata")
 * @ORM\Entity(repositoryClass="FOS\MessageBundle\Repository\ThreadMetadataRepository")
 */
class ThreadMetadata extends AbstractThreadMetadata
{
    public const TABLE_NAME = 'fos_message_thread_metadata';

    public const TABLE_COLUMN_ID                               = 'id';
    public const TABLE_COLUMN_THREAD_ID                        = 'thread_id';
    public const TABLE_COLUMN_PARTICIPANT_ID                   = 'participant_id';
    public const TABLE_COLUMN_IS_DELETED                       = 'is_deleted';
    public const TABLE_COLUMN_LAST_MESSAGE_DATE                = 'last_message_date';
    public const TABLE_COLUMN_LAST_PARTICIPANT_MESSAGE_DATE    = 'last_participant_message_date';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Thread",
     *   inversedBy="metadata"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="thread_id", referencedColumnName="id")
     * })
     *
     * @var Thread
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="FOS\MessageBundle\Model\ParticipantInterface")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="participant_id", referencedColumnName="id")
     * })
     * @var ParticipantInterface
     */
    protected $participant;

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

    public function __construct()
    {
        $this->setLastMessageDate(new DateTime());
    }
}
