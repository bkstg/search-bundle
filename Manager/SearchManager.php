<?php

namespace Bkstg\SearchBundle\Manager;

use Bkstg\SearchBundle\Event\FieldCollectionEvent;
use Bkstg\SearchBundle\Event\QueryAlterEvent;
use Elastica\Query;
use Elastica\Search;
use FOS\ElasticaBundle\Elastica\Client;
use FOS\ElasticaBundle\Index\IndexManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class SearchManager implements SearchManagerInterface
{
    private $index_manager;
    private $dispatcher;
    private $token_storage;
    private $twig;
    private $client;

    public function __construct(
        IndexManager $index_manager,
        EventDispatcherInterface $dispatcher,
        TokenStorageInterface $token_storage,
        Environment $twig,
        Client $client
    ) {
        $this->index_manager = $index_manager;
        $this->dispatcher = $dispatcher;
        $this->token_storage = $token_storage;
        $this->twig = $twig;
        $this->client = $client;
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
    public function buildQuery(string $query_string)
    {
        // Default query string is everything.
        if ($query_string == '') {
            $query_string = '*';
        }

        // Search all indexes.
        $search = new Search($this->client);
        foreach($this->index_manager->getAllIndexes() as $index) {
            $search->addIndex($index);
        }
        $search->setQuery($query_string);
        d($search);
        d($search->search());
        d($this->index_manager);

        // Collect the fields that should be searchable.
        $fields_event = new FieldCollectionEvent();
        $this->dispatcher->dispatch(FieldCollectionEvent::NAME, $fields_event);

        // Auto-filter to the groups for this user.
        $user = $this->token_storage->getToken()->getUser();
        $memberships = $user->getMemberships();
        $group_ids = [];
        foreach ($memberships as $membership) {
            if ($membership->isActive() && !$membership->isExpired()) {
                $group_ids[] = $membership->getGroup()->getId();
            }
        }

        // Build the default query from twig template.
        $default_query = $this->twig->render('@BkstgSearch/Query/default_query.json.twig', [
            'query_string' => $query_string,
            'fields' => $fields_event->getFields(),
            'group_ids' => $group_ids,
        ]);

        // Decode the json so that the parameters are available.
        $query = new Query(json_decode($default_query, true));

        // Allow altering of the default query.
        $query_event = new QueryAlterEvent($query);
        $this->dispatcher->dispatch(QueryAlterEvent::NAME, $query_event);

        // Return the query.
        return $query;
    }

    public function execute(string $query)
    {
        $query = $this->buildQuery($query);
        return $query->execute();
    }
}
