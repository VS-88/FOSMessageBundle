<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_message_thread")
 * @ORM\Entity(repositoryClass="FOS\MessageBundle\Repository\ThreadRepository")
 */
class Thread extends \FOS\MessageBundle\Model\Thread
{
    public const TABLE_NAME = 'fos_message_thread';

    public const TABLE_COLUMN_ID            = 'id';
    public const TABLE_COLUMN_IS_SPAM       = 'is_spam';
    public const TABLE_COLUMN_SUBJECT       = 'subject';
    public const TABLE_COLUMN_KEYWORDS      = 'keywords';
    public const TABLE_COLUMN_CREATED_BY    = 'created_by';
    public const TABLE_COLUMN_CREATED_AT    = 'created_at';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="FOS\MessageBundle\Model\ParticipantInterface",)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     *
     * @var ParticipantInterface
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Message",
     *   mappedBy="thread"
     * )
     * @var Message[]|Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|Collection
     */
    protected $metadata;

    /**
     * All text contained in the thread messages
     * Used for the full text search.
     *
     * @ORM\Column(name="keywords", type="string", nullable=false)
     *
     * @var string
     */
    protected $keywords = '';

    /**
     * @ORM\Column(name="subject", type="string", nullable=false)
     *
     * @var string
     */
    protected $subject;

    /**
     * @ORM\Column(name="is_spam", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $isSpam = false;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @var DateTime
     */
    protected $createdAt;
}
