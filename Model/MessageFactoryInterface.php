<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Model;

/**
 * Class MessageFactory
 */
interface MessageFactoryInterface
{
    /**
     * @return MessageInterface
     */
    public function create(): MessageInterface;

    /**
     * @return string
     */
    public function getEntityClass(): string;
}
