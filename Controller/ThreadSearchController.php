<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Search\FinderInterface;
use FOS\MessageBundle\Search\QueryFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThreadSearchController
 */
class ThreadSearchController extends AbstractController
{
    /**
     * @var QueryFactoryInterface
     */
    private $queryFactory;

    /**
     * @var FinderInterface
     */
    private $finder;

    private $view;

    /**
     * ThreadSearchController constructor.
     * @param QueryFactoryInterface $queryFactory
     * @param FinderInterface $finder
     * @param string $view
     */
    public function __construct(
        QueryFactoryInterface $queryFactory,
        FinderInterface $finder,
        string $view = '@FOSMessage/Message/search.html.twig'
    ) {
        $this->queryFactory = $queryFactory;
        $this->finder       = $finder;
        $this->view         = $view;
    }

    /**
     * Searches for messages in the inbox and sentbox.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $query   = $this->queryFactory->createFromRequest($request);
        $threads = $this->finder->find($query);

        return $this->render($this->view, compact('query', 'threads'));
    }
}
