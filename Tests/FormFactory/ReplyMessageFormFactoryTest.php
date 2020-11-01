<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Tests\FormFactory;

use FOS\MessageBundle\Entity\Thread;
use FOS\MessageBundle\FormFactory\ReplyMessageFormFactory;
use FOS\MessageBundle\FormModel\ReplyMessage;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Instanciates message forms.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ReplyMessageFormFactoryTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function create(): void
    {
        $thread = new Thread();

        $formFactory = \Mockery::mock(FormFactoryInterface::class);

        $factory = new ReplyMessageFormFactory(
            $formFactory,
            'someType',
            'someName',
            ReplyMessage::class
        );

        $message = new ReplyMessage();
        $message->setThread($thread);

        $compare = static function ($m) use ($message) {
            return serialize($m) === serialize($message);
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
