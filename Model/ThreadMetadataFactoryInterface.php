<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

/**
 * Class ThreadMetadataFactory
 */
interface ThreadMetadataFactoryInterface
{
    /**
     * @return ThreadMetadataInterface
     */
    public function create(): ThreadMetadataInterface;

    /**
     * @return string
     */
    public function getEntityClass(): string;
}
