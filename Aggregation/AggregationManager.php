<?php

namespace Bkstg\SearchBundle\Aggregation;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

class AggregationManager implements AggregationManagerInterface
{
    private $processors;
    private $request_stack;

    public function __construct(RequestStack $request_stack)
    {
        $this->processors = [];
        $this->request_stack = $request_stack;
    }

    public function addProcessor(AggregationProcessorInterface $processor)
    {
        $this->processors[$processor->getName()] = $processor;
    }

    public function hasProcessor(string $name): bool
    {
        return isset($this->processors[$name]);
    }

    public function getProcessor(string $name): ?AggregationProcessorInterface
    {
        return $this->processors[$name] ?: null;
    }

    public function getProcessors(): array
    {
        return $this->processors;
    }

    public function getQuery(): AbstractQuery
    {
        // Get the current request from the stack.
        $request = $this->request_stack->getCurrentRequest();

        // Create a query.
        $query = new BoolQuery();

        // Iterate over processors, adding to query.
        foreach ($this->processors as $processor) {
            $key = urlencode($processor->getName());
            if ($request->query->has($key)) {
                $query->addMust(
                    $processor->getQuery($request->query->get($key))
                );
            }
        }

        // Return the query for all active aggregations.
        return $query;
    }

    public function buildLinks(array $aggregations): array
    {
        $request = $this->request_stack->getCurrentRequest();
        foreach($this->processors as $processor) {
            $key = urlencode($processor->getName());
            $processor->buildLinks(
                $aggregations[$processor->getName()] ?: null,
                $request->query->get($key, null)
            );
        }
        return $this->processors;
    }
}
