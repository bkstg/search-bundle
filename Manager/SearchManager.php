<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Manager;

use Bkstg\SearchBundle\Aggregation\AggregationManagerInterface;
use Bkstg\SearchBundle\Event\FieldCollectionEvent;
use Bkstg\SearchBundle\Event\FilterCollectionEvent;
use Bkstg\SearchBundle\Event\QueryAlterEvent;
use Elastica\Query;
use Elastica\QueryBuilder;
use FOS\ElasticaBundle\Finder\FinderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SearchManager implements SearchManagerInterface
{
    private $results;
    private $aggregations;

    private $dispatcher;
    private $token_storage;
    private $finder;
    private $aggregation_manager;
    private $request_stack;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        TokenStorageInterface $token_storage,
        FinderInterface $finder,
        AggregationManagerInterface $aggregation_manager,
        RequestStack $request_stack
    ) {
        $this->dispatcher = $dispatcher;
        $this->token_storage = $token_storage;
        $this->finder = $finder;
        $this->aggregation_manager = $aggregation_manager;
        $this->request_stack = $request_stack;
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
     *
     * @param string $query_string
     */
    public function buildQuery(string $query_string): Query
    {
        // Get the current request.
        $request = $this->request_stack->getCurrentRequest();

        // Default query string is everything.
        if ('' == $query_string) {
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
        foreach ($filter_event->getFilters() as $filter) {
            $filter_query->addShould($filter);
        }

        // Create the query.
        $query = new Query();
        $query->setQuery($search_query);

        // Get aggregations to add to query.
        foreach ($this->aggregation_manager->getProcessors() as $processor) {
            $query->addAggregation($processor->getAggregation());
        }

        // Add aggregation filters to query.
        $search_query->addMust($this->aggregation_manager->getQuery());

        // Allow altering of the finished query.
        $query_event = new QueryAlterEvent($query);
        $this->dispatcher->dispatch(QueryAlterEvent::NAME, $query_event);

        return $query;
    }

    public function execute(Query $query)
    {
        $this->results = $this->finder->createPaginatorAdapter($query);
        $this->aggregations = $this->aggregation_manager->buildLinks(
            $this->results->getAggregations()
        );

        return $this->results;
    }

    public function getAggregations()
    {
        return $this->aggregations;
    }

    public function getResults()
    {
        return $this->results;
    }
}
