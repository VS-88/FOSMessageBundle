<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Search;

use Doctrine\ORM\QueryBuilder;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;

/**
 * Finds threads of a participant, matching a given query.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class Finder implements FinderInterface
{
    /**
     * The participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * The thread manager.
     *
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * Finder constructor.
     *
     * @param ParticipantProviderInterface $participantProvider
     * @param ThreadManagerInterface $threadManager
     */
    public function __construct(
        ParticipantProviderInterface $participantProvider,
        ThreadManagerInterface $threadManager
    ) {
        $this->participantProvider = $participantProvider;
        $this->threadManager       = $threadManager;
    }

    /**
     * {@inheritdoc}
     */
    public function find(Query $query): array
    {
        return $this
            ->threadManager
            ->findParticipantThreadsBySearch(
                $this->getAuthenticatedParticipant(),
                $query->getEscaped()
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilder(Query $query): QueryBuilder
    {
        return $this->threadManager->getParticipantThreadsBySearchQueryBuilder(
            $this->getAuthenticatedParticipant(),
            $query->getEscaped()
        );
    }

    /**
     * Gets the current authenticated user.
     *
     * @return ParticipantInterface
     */
    protected function getAuthenticatedParticipant(): ParticipantInterface
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }
}
