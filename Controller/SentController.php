<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class SentControllerTest
 */
class SentController extends AbstractProviderAwareController
{
    /**
     * Displays the authenticated participant messages sent.
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $threads = $this->provider->getSentThreads();

        return $this->render($this->templatePath, [
            'threads' => $threads,
        ]);
    }
}
