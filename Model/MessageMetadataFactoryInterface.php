<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageFactory
 */
interface MessageMetadataFactoryInterface
{
    /**
     * @return MessageMetadataInterface
     */
    public function create(): MessageMetadataInterface;

    /**
     * @return string
     */
    public function getEntityClass(): string;
}
