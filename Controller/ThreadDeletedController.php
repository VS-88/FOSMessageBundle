<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThreadDeletedController
 */
class ThreadDeletedController extends AbstractProviderAwareController
{
    /**
     * Displays the authenticated participant deleted threads.
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $threads = $this->provider->getDeletedThreads();

        return $this->render($this->templatePath, [
            'threads' => $threads,
        ]);
    }
}
