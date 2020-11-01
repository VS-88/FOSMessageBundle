<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_message_message")
 * @ORM\Entity(repositoryClass="FOS\MessageBundle\Repository\MessageRepository")
 */
class Message extends \FOS\MessageBundle\Model\Message
{
    public const TABLE_NAME = 'fos_message_message';

    public const TABLE_COLUMN_ID            = 'id';
    public const TABLE_COLUMN_THREAD_ID     = 'thread_id';
    public const TABLE_COLUMN_SENDER_ID     = 'sender_id';
    public const TABLE_COLUMN_IS_MODERATED  = 'is_moderated';
    public const TABLE_COLUMN_BODY          = 'body';
    public const TABLE_COLUMN_CREATED_AT    = 'created_at';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Thread",
     *   inversedBy="messages"
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
     *   @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     * })
     *
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata[]|Collection
     */
    protected $metadata;

    /**
     * @ORM\OneToMany(
     *   targetEntity="MessageAttachment",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageAttachment[]|Collection
     */
    protected $messageAttachments;

    /**
     * @ORM\Column(name="is_moderated", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $isModerated = 0;

    /**
     * @ORM\Column(name="body", type="text", nullable=false)
     *
     * @var string
     */
    protected $body;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @var DateTime
     */
    protected $createdAt;
}
