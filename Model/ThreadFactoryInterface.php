<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Model;

/**
 * Class ThreadFactory
 */
interface ThreadFactoryInterface
{
    /**
     * @return ThreadInterface
     */
    public function create(): ThreadInterface;

    /**
     * @return string
     */
    public function getEntityClass(): string;
}
