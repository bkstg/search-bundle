<?php

namespace Bkstg\SearchBundle\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Query\AbstractQuery;

interface AggregationProcessorInterface
{
    public function getName(): string;
    public function getAggregation(): AbstractAggregation;
    public function getQuery($value): AbstractQuery;
    public function getLabel(): string;
    public function buildLinks($aggregation, $value);
    public function getLinks(): array;
    public function isActive(): bool;
}
