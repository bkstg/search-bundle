<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
