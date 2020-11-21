<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use FOS\MessageBundle\FOSMessageBundle;
use FOS\MessageBundle\Tests\Functional\Entity\UserProvider;
use FOS\MessageBundle\Tests\Functional\Form\UserToUsernameTransformer;
use FOS\MessageBundle\Tests\Functional\Repository\DummyParticipantRepository;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use FOS\MessageBundle\Tests\Functional\Entity\User;

/**
 * @author Guilhem N. <guilhem.niot@gmail.com>
 */
class TestKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new FOSMessageBundle(),
            new KnpPaginatorBundle(),
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     * @throws LoaderLoadException
     */
    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $routes->import('@FOSMessageBundle/Resources/config/routing.yaml');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/services_test.yaml');

        $c->loadFromExtension('framework', [
            'secret' => 'MySecretKey',
            'test' => null,
            'form' => null,
            'assets' => null,
            'session' => [
                'handler_id'      => 'session.handler.native_file',
                'save_path'       => '%kernel.project_dir%/var/sessions/%kernel.environment%',
                'cookie_lifetime' => 2592000,
                'cookie_secure'   => 'auto',
                'cookie_samesite' => 'lax',
            ],
        ]);

        $c->loadFromExtension('security', [
            'providers' => ['permissive' => ['id' => 'app.user_provider']],
            'encoders' => [User::class => 'plaintext'],
            'firewalls' => ['main' => ['http_basic' => true]],
        ]);

        $c->loadFromExtension('twig', [
            'strict_variables' => '%kernel.debug%',
        ]);

        $c->loadFromExtension('doctrine', json_decode($_ENV['doctrine_config_as_json'], true));

        $c->loadFromExtension('fos_message', [
            'db_driver' => 'orm',
            'path_to_message_attachments_dir' => __DIR__ . DIRECTORY_SEPARATOR . 'var',
        ]);

        $c->register('fos_user.user_to_username_transformer', UserToUsernameTransformer::class);
        $c->register('app.user_provider', UserProvider::class);

        $c->addCompilerPass(new RegisteringManagersPass());
    }
}
