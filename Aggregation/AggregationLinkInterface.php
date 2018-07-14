<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Aggregation;

interface AggregationLinkInterface
{
    public function isActive(): bool;

    public function getQuery(): array;

    public function getLabel(): string;

    public function getCount(): int;

    public function getProcessor(): AggregationProcessorInterface;
}
