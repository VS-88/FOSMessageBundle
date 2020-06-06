<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\Thread as BaseThread;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ThreadMetadataInterface;
use InvalidArgumentException;

/**
 * Class Thread
 */
abstract class AbstractThread extends BaseThread
{
    /**
     * All text contained in the thread messages
     * Used for the full text search.
     *
     * @var string
     */
    protected $keywords = '';

    /**
     * @ORM\Column(name="subject", type="string", nullable=false)
     *
     * @var string
     */
    protected $subject;

    /**
     * @ORM\Column(name="is_spam", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $isSpam = false;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * {@inheritdoc}
     */
    public function getParticipants(): Collection
    {
        return $this->getParticipantsCollection();
    }

    /**
     * Gets the users participating in this conversation.
     *
     * Since the ORM schema does not map the participants collection field, it
     * must be created on demand.
     *
     * @return ArrayCollection|ParticipantInterface[]
     */
    protected function getParticipantsCollection(): Collection
    {
        if ($this->participants === null) {
            $this->participants = new ArrayCollection();

            foreach ($this->metadata as $data) {
                $this->participants->add($data->getParticipant());
            }
        }

        return $this->participants;
    }

    /**
     * {@inheritdoc}
     */
    public function addParticipant(ParticipantInterface $participant): ThreadInterface
    {
        if (!$this->isParticipant($participant)) {
            $this->getParticipantsCollection()->add($participant);
        }

        return  $this;
    }

    /**
     * Adds many participants to the thread.
     *
     * @param iterable
     *
     * @return AbstractThread
     *@throws InvalidArgumentException
     *
     */
    public function addParticipants(iterable $participants): AbstractThread
    {
        foreach ($participants as $participant) {
            $this->addParticipant($participant);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isParticipant(ParticipantInterface $participant): bool
    {
        return $this->getParticipantsCollection()->contains($participant);
    }

    /**
     * Get the collection of ModelThreadMetadata.
     *
     * @return Collection
     */
    public function getAllMetadata(): Collection
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function addMetadata(ThreadMetadataInterface $meta): ThreadInterface
    {
        $meta->setThread($this);
        parent::addMetadata($meta);

        return $this;
    }
}
