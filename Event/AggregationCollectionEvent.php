<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Event;

use Elastica\Aggregation\AbstractAggregation;
use Symfony\Component\EventDispatcher\Event;

class AggregationCollectionEvent extends Event
{
    const NAME = 'bkstg_search.aggregation_collection';

    private $aggregations = [];

    public function addAggregation(AbstractAggregation $aggregation): void
    {
        $this->aggregations[] = $aggregation;
    }

    public function getAggregations()
    {
        return $this->aggregations;
    }
}
