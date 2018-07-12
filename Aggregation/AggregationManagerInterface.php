<?php

namespace Bkstg\SearchBundle\Aggregation;

use Elastica\Query\AbstractQuery;

interface AggregationManagerInterface
{
    public function addProcessor(AggregationProcessorInterface $processor);
    public function hasProcessor(string $name): bool;
    public function getProcessor(string $name): ?AggregationProcessorInterface;
    public function getProcessors(): array;
    public function buildLinks(array $aggregations): array;
    public function getQuery(): AbstractQuery;
}
