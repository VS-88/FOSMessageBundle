<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Model\ParticipantInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserMessagesListAwareController
 */
class UserMessagesListAwareController extends AbstractController
{
    /**
     * @var callable
     */
    private $provider;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var int
     */
    private $limit;

    /**
     * UserMessagesListAwareController constructor.
     * @param string $messageInboxTemplatePath
     * @param int $limit
     * @param callable $provider
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        string $messageInboxTemplatePath,
        int $limit,
        callable $provider,
        PaginatorInterface $paginator
    ) {
        $this->provider     = $provider;
        $this->templatePath = $messageInboxTemplatePath;
        $this->paginator    = $paginator;
        $this->limit        = $limit;
    }

    /**
     * Displays the authenticated participant inbox.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        /**
         * @var ParticipantInterface $user
         */
        $user  = $this->getUser();

        $query = call_user_func($this->provider, $user);

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->limit
        );

        return $this->render($this->templatePath, [
            'list'                => $pagination,
        ]);
    }
}
