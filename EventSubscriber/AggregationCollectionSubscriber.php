<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\EventSubscriber;

use Bkstg\SearchBundle\Event\AggregationCollectionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AggregationCollectionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AggregationCollectionEvent::NAME => [
                ['addTypeAggregation', 0],
                ['addGroupAggregation', 0],
            ],
        ];
    }

    public function addTypeAggregation(AggregationCollectionEvent $event): void
    {
        d($event);
    }

    public function addGroupAggregation(AggregationCollectionEvent $event): void
    {
    }
}
