<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Event;

use Elastica\Query\AbstractQuery;
use Elastica\QueryBuilder;
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
