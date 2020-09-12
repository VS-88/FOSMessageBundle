<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\Functional;

use FOS\MessageBundle\FOSMessageBundle;
use FOS\MessageBundle\Tests\Functional\Entity\Message;
use FOS\MessageBundle\Tests\Functional\Entity\MessageAttachment;
use FOS\MessageBundle\Tests\Functional\Entity\Thread;
use FOS\MessageBundle\Tests\Functional\Entity\UserProvider;
use FOS\MessageBundle\Tests\Functional\EntityManager\MessageManager;
use FOS\MessageBundle\Tests\Functional\EntityManager\ThreadManager;
use FOS\MessageBundle\Tests\Functional\Form\UserToUsernameTransformer;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
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
        $c->loadFromExtension('framework', [
            'secret' => 'MySecretKey',
            'test' => null,
            'form' => null,
            'assets' => null,
        ]);

        $c->loadFromExtension('security', [
            'providers' => ['permissive' => ['id' => 'app.user_provider']],
            'encoders' => [User::class => 'plaintext'],
            'firewalls' => ['main' => ['http_basic' => true]],
        ]);

        $c->loadFromExtension('twig', [
            'strict_variables' => '%kernel.debug%',
        ]);

        $c->loadFromExtension('fos_message', [
            'db_driver' => 'orm',
            'thread_class' => Thread::class,
            'message_class' => Message::class,
            'message_attachment_class' => MessageAttachment::class,
            'path_to_message_attachments_dir' => 'some_path'
        ]);

        $c->register('fos_user.user_to_username_transformer', UserToUsernameTransformer::class);
        $c->register('app.user_provider', UserProvider::class);
        $c->addCompilerPass(new RegisteringManagersPass());
    }
}

/**
 * Class RegisteringManagersPass
 * @package FOS\MessageBundle\Tests\Functional
 */
class RegisteringManagersPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $container->register('fos_message.message_manager.default', MessageManager::class);
        $container->register('fos_message.thread_manager.default', ThreadManager::class);
    }
}
