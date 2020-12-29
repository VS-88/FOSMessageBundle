<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\Deleter\DeleterInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ThreadDeleteController
 */
class ThreadDeleteController extends AbstractController
{
    /**
     * @var ThreadManagerInterface
     */
    private $threadManager;

    /**
     * @var DeleterInterface
     */
    protected $deleter;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * ThreadDeleteController constructor.
     *
     * @param DeleterInterface $deleter
     * @param ThreadManagerInterface $threadManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        DeleterInterface $deleter,
        ThreadManagerInterface $threadManager,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->threadManager = $threadManager;
        $this->deleter       = $deleter;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Deletes a thread.
     *
     * @param int $threadId the thread id
     *
     * @return RedirectResponse
     */
    public function indexAction(int $threadId): Response
    {
        /**
         * @var EntityManager $em
         */
        $thread = $this->threadManager->findThreadById($threadId);

        if ($thread !== null) {
            if ($this->authorizationChecker->isGranted('DELETE', $thread) === false) {
                throw $this->createAccessDeniedException();
            }
        } else {
            $this->createNotFoundException();
        }

        $this->deleter->markAsDeleted($thread);
        $this->threadManager->saveThread($thread);

        return $this->redirectToRoute('fos_message_inbox');
    }
}
