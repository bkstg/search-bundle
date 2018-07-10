<?php

namespace Bkstg\SearchBundle\Event;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Symfony\Component\EventDispatcher\Event;

class FilterCollectionEvent extends Event
{
    const NAME = 'bkstg_search.filter_collection';

    private $filters = [];
    private $group_ids;
    private $qb;

    public function __construct(QueryBuilder $qb, array $group_ids)
    {
        $this->group_ids = $group_ids;
        $this->qb = $qb;
    }

    public function addFilter(AbstractQuery $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getQueryBuilder()
    {
        return $this->qb;
    }

    public function getGroupIds()
    {
        return $this->group_ids;
    }
}
