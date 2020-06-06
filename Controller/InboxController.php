<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class InboxControllerTest
 */
class InboxController extends AbstractProviderAwareController
{
    /**
     * Displays the authenticated participant inbox.
     *
     * @return Response
     */
    public function inboxAction(): Response
    {
        $threads = $this->provider->getInboxThreads();

        return $this->render($this->templatePath, [
            'threads' => $threads,
        ]);
    }
}
