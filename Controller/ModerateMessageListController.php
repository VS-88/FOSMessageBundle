<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Provider\ModerationAwareMessageProviderInterface;
use Knp\Component\Pager\PaginatorInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ModerateMessageListController
 */
class ModerateMessageListController extends AbstractController
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var string
     */
    private $listContainerId;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var ModerationAwareMessageProviderInterface
     */
    private $listProvider;

    /**
     * CabinetController constructor.
     *
     * @param PaginatorInterface $paginator
     * @param string $actionsTemplatePath
     * @param string $listContainerId
     * @param int $limit
     * @param ModerationAwareMessageProviderInterface $listProvider
     */
    public function __construct(
        string $actionsTemplatePath,
        string $listContainerId,
        int $limit,
        PaginatorInterface $paginator,
        ModerationAwareMessageProviderInterface $listProvider
    ) {
        $this->paginator           = $paginator;
        $this->templatePath = $actionsTemplatePath;
        $this->listContainerId     = $listContainerId;
        $this->limit               = $limit;
        $this->listProvider        = $listProvider;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws LogicException
     */
    public function indexAction(Request $request): Response
    {
        $query = $this->listProvider->getMessagesByModerationFlag(false);

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->limit
        );

        return $this->render($this->templatePath, [
            'list'                => $pagination,
            'listContainerId'     => $this->listContainerId,
        ]);
    }
}
