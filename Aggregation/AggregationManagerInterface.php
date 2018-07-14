<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
