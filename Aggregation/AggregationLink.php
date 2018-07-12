<?php

namespace Bkstg\SearchBundle\Aggregation;

class AggregationLink implements AggregationLinkInterface
{
    private $query;
    private $label;
    private $count;
    private $active;
    private $processor;

    public function __construct(AggregationProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function getProcessor(): AggregationProcessorInterface
    {
        return $this->processor;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setQuery(array $query)
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setCount(int $count)
    {
        $this->count = $count;
        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

}
