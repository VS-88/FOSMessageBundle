<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\FormFactory;

use FOS\MessageBundle\FormFactory\NewThreadMessageFormFactory;
use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class NewThreadMessageFormFactoryTest
 * @package FOS\MessageBundle\Tests\FormFactory
 */
class NewThreadMessageFormFactoryTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function create(): void
    {
        $thread = null;

        $formFactory = \Mockery::mock(FormFactoryInterface::class);

        $factory = new NewThreadMessageFormFactory(
            $formFactory,
            'someType',
            'someName',
            NewThreadMessage::class
        );

        $compare = static function ($message) {
            return $message instanceof AbstractMessage;
        };

        $formMock = \Mockery::mock(FormInterface::class);

        $formFactory->shouldReceive('createNamed')
            ->once()
            ->with('someName', 'someType', \Mockery::on($compare))
            ->andReturn($formMock);

        $actual = $factory->create($thread);

        self::assertSame($formMock, $actual);
    }
}
