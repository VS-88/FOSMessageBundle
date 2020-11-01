<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_message_message_metadata")
 * @ORM\Entity(repositoryClass="FOS\MessageBundle\Repository\MessageMetadataRepository")
 */
class MessageMetadata extends \FOS\MessageBundle\Model\MessageMetadata
{
    public const TABLE_NAME                   = 'fos_message_message_metadata';

    public const TABLE_COLUMN_ID               = 'id';
    public const TABLE_COLUMN_MESSAGE_ID       = 'message_id';
    public const TABLE_COLUMN_PARTICIPANT_ID   = 'participant_id';
    public const TABLE_COLUMN_FILE_IS_READ     = 'is_read';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true}))
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Message",
     *   inversedBy="metadata"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * })
     * @var Message
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="FOS\MessageBundle\Model\ParticipantInterface")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="participant_id", referencedColumnName="id")
     * })
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * @ORM\Column(name="is_read", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $isRead = false;
}
