<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests;

use App\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Exception;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Tests\Functional\Entity\DummyParticipant;
use FOS\MessageBundle\Tests\Functional\WebTestCase;
use Mockery;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * Class AbstractDataBaseTestCase
 */
abstract class AbstractDataBaseTestCase extends WebTestCase
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var KernelBrowser
     */
    protected $kernelBrowser;

    /**
     * @var TestContainer
     */
    protected $testContainer;

    /**
     * @var ORMPurger
     */
    private $purger;

    private static $tablesAreCreated = false;

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->kernelBrowser = static::createClient(
            $this->getClientOptions(),
            $this->getClientServerParams()
        );

        $container = $this->kernelBrowser->getContainer();

        $testContainer = $container->get('test.service_container');
        $this->testContainer = $testContainer;

        $this->managerRegistry = $testContainer->get('doctrine');
        $this->em = $this->managerRegistry->getManager();

        $this->purger = new ORMPurger($this->em);

        $this->lazyCreateTables($this->testContainer);
        $this->purger->purge();

        $this->em->beginTransaction();

        $this->loadFixtures($this->testContainer, $this->getFixtures());

        $this->em->commit();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    protected function tearDown(): void
    {
        Mockery::close();

        $this->purger->purge();

        parent::tearDown();
    }

    /**
     * @return array
     */
    abstract protected function getFixtures(): array;

    /**
     * @param ContainerInterface $container
     * @param array $fixtureClassNames
     *
     * @return void
     *
     * @throws Exception
     */
    private function loadFixtures(ContainerInterface $container, array $fixtureClassNames): void
    {
        $loadFixturesCommand = $container->get('doctrine.fixtures_load_command');

        $commandTester = new CommandTester($loadFixturesCommand);

        $commandTester->execute(
            [
                '--group' => $fixtureClassNames,
                '--append' => true,
            ]
        );
    }

    /**
     * @return array
     */
    protected function getClientOptions(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getClientServerParams(): array
    {
        return [];
    }

    /**
     * @param ContainerInterface $container
     *
     * @throws Exception
     */
    private function lazyCreateTables(ContainerInterface $container): void
    {
        if (self::$tablesAreCreated === false) {
            $helperSet = new HelperSet([
                new FormatterHelper(),
                new DebugFormatterHelper(),
                new ProcessHelper(),
                new QuestionHelper(),
            ]);

            $helperSet->set(new ConnectionHelper($this->em->getConnection()), 'db');
            $helperSet->set(new EntityManagerHelper($this->em), 'em');

            /**
             * @var KernelInterface $kernel
             */
            $kernel = $this->testContainer->get('kernel');

            $app = new Application($kernel);
            $app->setHelperSet($helperSet);

            $schemaUpdateCommand = $container->get('doctrine.schema_update_command');
            $schemaUpdateCommand->setApplication($app);


            $commandTester = new CommandTester($schemaUpdateCommand);

            $commandTester->execute(
                [
                    '--force' => true,
                ]
            );

            self::$tablesAreCreated = true;
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @param array $rolesToAppend
     *
     * @return UserInterface
     *
     * @throws Exception
     */
    protected function logIn(string $email, string $password, array $rolesToAppend): UserInterface
    {
        $session = $this->testContainer->get('session');

        /**
         * @var UserInterface[] $users
         */
        $users = $this->em->getRepository(DummyParticipant::class)->findBy(['email' => $email]);


        if (empty($users)) {
            $user  = new DummyParticipant();

            $user
                ->setEmail($email)
                ->setPassword($password)
                ->setRoles($rolesToAppend);
        } else {
            $user = reset($users);

            $user->setRoles(
                array_unique(array_merge($user->getRoles(), $rolesToAppend))
            );
        }

        $this->em->persist($user);
        $this->em->flush();

        $token = new PostAuthenticationGuardToken($user, 'app_user_provider', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->kernelBrowser->getCookieJar()->set($cookie);

        return $user;
    }
}
