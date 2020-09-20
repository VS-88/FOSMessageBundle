<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SingleRecipientDataTransformer
 * @package FOS\MessageBundle\DataTransformer
 */
class SingleRecipientDataTransformer implements DataTransformerInterface
{
    /**
     * @var DataTransformerInterface
     */
    private $userToUsernameTransformer;

    /**
     * RecipientsDataTransformer constructor.
     * @param DataTransformerInterface $userToUsernameTransformer
     */
    public function __construct(DataTransformerInterface $userToUsernameTransformer)
    {
        $this->userToUsernameTransformer = $userToUsernameTransformer;
    }

    /**
     * Transforms a collection of recipients into a string.
     *
     * @param UserInterface $recipient
     *
     * @return string
     */
    public function transform($recipient): string
    {
        if (is_object($recipient) === false || ($recipient instanceof UserInterface) === false) {
            return '';
        }

        return $this->userToUsernameTransformer->transform($recipient);
    }

    /**
     * Transforms a string (usernames) to a Collection of UserInterface.
     *
     * @param string $username
     *
     * @throws UnexpectedTypeException
     * @throws TransformationFailedException
     *
     * @return ?UserInterface
     */
    public function reverseTransform($username): ?UserInterface
    {
        if ($username === null || $username === '') {
            return null;
        }

        if (!is_string($username)) {
            throw new UnexpectedTypeException($username, 'string');
        }

        return $this->userToUsernameTransformer->reverseTransform(trim($username));
    }
}
