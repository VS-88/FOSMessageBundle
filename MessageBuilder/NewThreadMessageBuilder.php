<?php
declare(strict_types=1);

namespace FOS\MessageBundle\MessageBuilder;

use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Fluent interface message builder for new thread messages.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageBuilder extends AbstractMessageBuilder
{
    /**
     * The thread subject.
     *
     * @param  string
     *
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function setSubject(string $subject): NewThreadMessageBuilder
    {
        $this->thread->setSubject($subject);

        return $this;
    }

    /**
     * @param ParticipantInterface $recipient
     *
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function addRecipient(ParticipantInterface $recipient): NewThreadMessageBuilder
    {
        $this->thread->addParticipant($recipient);

        return $this;
    }
}
