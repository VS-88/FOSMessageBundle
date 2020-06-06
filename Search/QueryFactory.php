<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Search;

use Symfony\Component\HttpFoundation\Request;

/**
 * Gets the search term from the request and prepares it.
 */
class QueryFactory implements QueryFactoryInterface
{
    /**
     * The query parameter containing the search term.
     *
     * @var string
     */
    protected $queryParameter;

    /**
     * Instanciates a new TermGetter.
     *
     * @param string $queryParameter
     */
    public function __construct(string $queryParameter)
    {
        $this->queryParameter = $queryParameter;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRequest(Request $request): Query
    {
        $original = (string) $request->query->get($this->queryParameter);
        $original = trim($original);

        $escaped = $this->escapeTerm($original);

        return new Query($original, $escaped);
    }

    /**
     * @param $term
     *
     * @return string
     */
    protected function escapeTerm(string $term): string
    {
        return $term;
    }
}
