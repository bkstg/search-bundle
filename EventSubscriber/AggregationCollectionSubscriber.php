<?php

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
            ]
        ];
    }

    public function addTypeAggregation(AggregationCollectionEvent $event)
    {
        d($event);
    }

    public function addGroupAggregation(AggregationCollectionEvent $event)
    {

    }
}
