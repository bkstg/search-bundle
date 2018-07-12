<?php

namespace Bkstg\SearchBundle\Event;

use Elastica\Aggregation\AbstractAggregation;
use Symfony\Component\EventDispatcher\Event;

class AggregationCollectionEvent extends Event
{
    const NAME = 'bkstg_search.aggregation_collection';

    private $aggregations = [];

    public function addAggregation(AbstractAggregation $aggregation)
    {
        $this->aggregations[] = $aggregation;
    }

    public function getAggregations()
    {
        return $this->aggregations;
    }
}
