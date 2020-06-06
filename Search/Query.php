<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Search;

/**
 * Search term.
 */
class Query
{
    /**
     * @var string
     */
    protected $original;

    /**
     * @var string
     */
    protected $escaped;

    /**
     * @param string $original
     * @param string $escaped
     */
    public function __construct(string $original, string $escaped)
    {
        $this->original = $original;
        $this->escaped  = $escaped;
    }

    /**
     * @return string original
     */
    public function getOriginal(): string
    {
        return $this->original;
    }

    /**
     * @param string $original
     */
    public function setOriginal(string $original): void
    {
        $this->original = $original;
    }

    /**
     * @return string escaped
     */
    public function getEscaped(): string
    {
        return $this->escaped;
    }

    /**
     * @param string $escaped
     */
    public function setEscaped(string $escaped): void
    {
        $this->escaped = $escaped;
    }

    /**
     * Converts to the original term string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getOriginal();
    }

    public function isEmpty(): bool
    {
        return empty($this->original);
    }
}
