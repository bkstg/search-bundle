<?php

namespace Bkstg\SearchBundle\Event;

use Elastica\Query\AbstractQuery;
use Symfony\Component\EventDispatcher\Event;

class QueryAlterEvent extends Event
{
    const NAME = 'bkstg_search.query_alter';

    private $query;

    public function __construct(AbstractQuery $query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
