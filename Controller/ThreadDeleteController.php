<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Deleter\DeleterInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * @var ProviderInterface
     */
    private $provider;

    /**
     * ThreadDeleteController constructor.
     *
     * @param ProviderInterface $provider
     * @param DeleterInterface $deleter
     * @param ThreadManagerInterface $threadManager
     */
    public function __construct(
        ProviderInterface $provider,
        DeleterInterface $deleter,
        ThreadManagerInterface $threadManager
    ) {
        $this->provider = $provider;

        $this->threadManager = $threadManager;
        $this->deleter       = $deleter;
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
        $thread = $this->provider->getThread($threadId);

        $this->processThread($thread);
        $this->threadManager->saveThread($thread);

        return $this->redirectToRoute('fos_message_inbox');
    }

    /**
     * @param ThreadInterface $thread
     */
    protected function processThread(ThreadInterface $thread): void
    {
        $this->deleter->markAsDeleted($thread);
    }
}
