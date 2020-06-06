<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Tests\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\DataTransformer\RecipientsDataTransformer;
use FOS\MessageBundle\Tests\AbstractTestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Transforms collection of UserInterface into strings separated with coma.
 *
 * @author Łukasz Pospiech <zocimek@gmail.com>
 */
class RecipientsDataTransformerTest extends AbstractTestCase
{
    /**
     * @var DataTransformerInterface|MockInterface
     */
    private $userToUsernameTransformer;

    /**
     * @var RecipientsDataTransformer
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userToUsernameTransformer = \Mockery::mock(DataTransformerInterface::class);

        $this->service = new RecipientsDataTransformer($this->userToUsernameTransformer);
    }

    /**
     * @test
     */
    public function transformCollectionCase(): void
    {
        $recipients = new ArrayCollection();

        $user1 = \Mockery::mock(UserInterface::class);
        $user2 = \Mockery::mock(UserInterface::class);

        $recipients->add($user1);
        $recipients->add($user2);

        $this->userToUsernameTransformer->shouldReceive('transform')
            ->once()
            ->with($user1)
            ->andReturn('name 1');

        $this->userToUsernameTransformer->shouldReceive('transform')
            ->once()
            ->with($user2)
            ->andReturn('name 2');

        $actual = $this->service->transform($recipients);

        self::assertSame('name 1, name 2', $actual);
    }

    /**
     * @test
     */
    public function transformInputIsNull(): void
    {
        $recipients = new ArrayCollection();

        $actual = $this->service->transform($recipients);

        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function transformInputIsEmpty(): void
    {
        $recipients = null;

        $actual = $this->service->transform($recipients);

        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function reverseTransformUsernamesCorrect(): void
    {
        $usernames = 'name 1, name 2';

        $expected = new ArrayCollection();

        $user1 = \Mockery::mock(UserInterface::class);
        $user2 = \Mockery::mock(UserInterface::class);

        $expected->add($user1);
        $expected->add($user2);

        $this->userToUsernameTransformer->shouldReceive('reverseTransform')
            ->once()
            ->with('name 1')
            ->andReturn($user1);

        $this->userToUsernameTransformer->shouldReceive('reverseTransform')
            ->once()
            ->with('name 2')
            ->andReturn($user2);

        $actual = $this->service->reverseTransform($usernames);

        self::assertSame(serialize($expected), serialize($actual));
    }

    /**
     * @test
     */
    public function reverseTransformUsernamesNullCase(): void
    {
        $usernames = null;

        $actual = $this->service->reverseTransform($usernames);

        self::assertNull($actual);
    }

    /**
     * @test
     */
    public function reverseTransformUsernamesEmptyString(): void
    {
        $usernames = '';

        $actual = $this->service->reverseTransform($usernames);

        self::assertNull($actual);
    }

    /**
     * @test
     */
    public function reverseTransformUsernamesCorrectExceptionCase(): void
    {
        $usernames = 'name 1, name 2';

        $this->expectException(TransformationFailedException::class);

        $this->userToUsernameTransformer->shouldReceive('reverseTransform')
            ->once()
            ->with('name 1')
            ->andReturn(new \stdClass());


        $this->service->reverseTransform($usernames);
    }

    /**
     * @test
     */
    public function reverseTransformUsernamesInvalidTypeExceptionCase(): void
    {
        $usernames = ['name 1, name 2'];

        $this->expectException(UnexpectedTypeException::class);

        $this->service->reverseTransform($usernames);
    }
}
