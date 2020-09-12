<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;


/**
 * Class MessageAttachmentFactory
 */
interface MessageAttachmentFactoryInterface
{
    /**
     * @return MessageAttachmentInterface
     */
    public function create(): MessageAttachmentInterface;

    /**
     * @return string
     */
    public function getEntityClass(): string;
}
