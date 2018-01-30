<?php

namespace Bkstg\SearchBundle\EventListener;

use Bkstg\SearchBundle\Event\FieldCollectionEvent;

class SearchFields
{
    public function addFields(FieldCollectionEvent $event)
    {
        $event->addFields([
            'name',
            'body',
            'author',
            'description',
        ]);
    }
}
