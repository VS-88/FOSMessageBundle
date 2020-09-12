<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\MessageAttachment as BaseMessageAttachment;

/**
 * Class AbstractMessageAttachment
 * @package FOS\MessageBundle\Entity
 */
abstract class AbstractMessageAttachment extends BaseMessageAttachment
{
    /**
     * @ORM\Column(name="file_name", type="string", nullable=false)
     *
     * @var string
     */
    protected $fileName;
}
