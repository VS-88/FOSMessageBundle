<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormModel;

/**
 * Class AbstractMessage
 * @package FOS\MessageBundle\FormModel
 */
abstract class AbstractMessage
{
    /**
     * @var string
     */
    protected $body;

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }
}
