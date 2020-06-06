<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormModel;

use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Class NewThreadMessage
 * @package FOS\MessageBundle\FormModel
 */
class NewThreadMessage extends AbstractMessage
{
    /**
     * The user who receives the message.
     *
     * @var ParticipantInterface
     */
    protected $recipient;

    /**
     * The thread subject.
     *
     * @var string
     */
    protected $subject;

    /**
     * @return string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return ParticipantInterface
     */
    public function getRecipient(): ?ParticipantInterface
    {
        return $this->recipient;
    }

    /**
     * @param ParticipantInterface $recipient
     */
    public function setRecipient(ParticipantInterface $recipient): void
    {
        $this->recipient = $recipient;
    }
}
