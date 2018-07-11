<?php

namespace Bkstg\SearchBundle\Manager;

use Bkstg\SearchBundle\Event\FieldCollectionEvent;
use Bkstg\SearchBundle\Event\FilterCollectionEvent;
use Bkstg\SearchBundle\Event\QueryAlterEvent;
use Elastica\Query;
use Elastica\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SearchManager implements SearchManagerInterface
{
    private $dispatcher;
    private $token_storage;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        TokenStorageInterface $token_storage
    ) {
        $this->dispatcher = $dispatcher;
        $this->token_storage = $token_storage;
    }

    /**
     * This function takes the query string and builds the search query.
     *
     * The default search query:
     *
     * - Queries all fulltext fields for the query string.
     * - Returns only productions the current user is a member of.
     * - Returns only non-productions that are in groups with the current user.
     * - Returns only entities that have a status of 1.
     */
    public function buildQuery(string $query_string): Query
    {
        // Default query string is everything.
        if ($query_string == '') {
            $query_string = '*';
        }

        // Collect the fields that should be searchable.
        $fields_event = new FieldCollectionEvent();
        $this->dispatcher->dispatch(FieldCollectionEvent::NAME, $fields_event);

        // Create the elastica query builder and default query.
        $qb = new QueryBuilder();
        $search_query = $qb->query()->bool()
            ->addMust(
                $qb
                    ->query()
                    ->query_string($query_string)
                    ->setFields($fields_event->getFields())
            )
        ;

        // Allow filters to use the current user's group ids.
        $user = $this->token_storage->getToken()->getUser();
        $memberships = $user->getMemberships();
        $group_ids = [];
        foreach ($memberships as $membership) {
            if ($membership->isActive() && !$membership->isExpired()) {
                $group_ids[] = $membership->getGroup()->getId();
            }
        }

        // Gather filters using an event.
        $filter_event = new FilterCollectionEvent($qb, $group_ids);
        $this->dispatcher->dispatch(FilterCollectionEvent::NAME, $filter_event);

        // Build the filter query.
        $filter_query = $qb->query()->bool();
        $search_query->addFilter($filter_query);

        // Add filters from the filter event.
        foreach($filter_event->getFilters() as $filter) {
            $filter_query->addShould($filter);
        }

        // Create the final returnable query.
        $query = new Query();
        $query->setQuery($search_query);

        // Allow altering of the finished query.
        $query_event = new QueryAlterEvent($query);
        $this->dispatcher->dispatch(QueryAlterEvent::NAME, $query_event);
        return $query;
    }

    public function execute(string $query)
    {
        $query = $this->buildQuery($query);
        return $query->execute();
    }
}
