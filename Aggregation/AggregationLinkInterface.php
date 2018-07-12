<?php

namespace Bkstg\SearchBundle\Aggregation;

interface AggregationLinkInterface
{
    public function isActive(): bool;
    public function getQuery(): array;
    public function getLabel(): string;
    public function getCount(): int;
    public function getProcessor(): AggregationProcessorInterface;
}
