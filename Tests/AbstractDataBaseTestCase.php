<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Exception;
use FOS\MessageBundle\Tests\Functional\WebTestCase;
use Mockery;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        $this->em->beginTransaction();

        $this->loadFixtures($this->testContainer, $this->getFixtures());
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $this->em->rollback();

        Mockery::close();

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
        $helperSet = new HelperSet([
            new FormatterHelper(),
            new DebugFormatterHelper(),
            new ProcessHelper(),
            new QuestionHelper(),
        ]);

        $helperSet->set(new ConnectionHelper($this->em->getConnection()), 'db');
        $helperSet->set(new EntityManagerHelper($this->em), 'em');

        $app = new Application($this->testContainer->get('kernel'));
        $app->setHelperSet($helperSet);

        $schemaUpdateCommand = $container->get('doctrine.schema_update_command');
        $schemaUpdateCommand->setApplication($app);

        $loadFixturesCommand = $container->get('doctrine.fixtures_load_command');

        $commandTester = new CommandTester($schemaUpdateCommand);

        $commandTester->execute(
            [
                '--force' => true,
            ]
        );

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
}
