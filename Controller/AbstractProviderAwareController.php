<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractProviderAwareController
 */
abstract class AbstractProviderAwareController extends AbstractController
{
    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * MessageController constructor.
     *
     * @param ProviderInterface $provider
     * @param string $messageInboxTemplatePath
     */
    public function __construct(ProviderInterface $provider, string $messageInboxTemplatePath)
    {
        $this->provider                 = $provider;
        $this->templatePath = $messageInboxTemplatePath;
    }
}
