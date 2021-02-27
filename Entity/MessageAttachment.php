<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="fos_message_message_attachment")
 * @ORM\Entity(repositoryClass="FOS\MessageBundle\Repository\MessageAttachmentRepository")
 */
class MessageAttachment extends \FOS\MessageBundle\Model\MessageAttachment
{
    public const TABLE_NAME = 'fos_message_message_attachment';

    public const TABLE_COLUMN_ID            = 'id';
    public const TABLE_COLUMN_MESSAGE_ID    = 'message_id';
    public const TABLE_COLUMN_FILE_NAME     = 'file_name';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true}))
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Message",
     *   inversedBy="messageAttachments"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * })
     * @var Message
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=false)
     */
    protected $fileName;
}
