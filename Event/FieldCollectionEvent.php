<?php

namespace Bkstg\SearchBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class FieldCollectionEvent extends Event
{
    const NAME = 'bkstg_search.field_collection';

    protected $fields = [];

    public function addField(string $field)
    {
        if (!in_array($field, $this->fields)) {
            $this->fields[] = $field;
        }
        return $this;
    }

    public function addFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    public function getFields()
    {
        return $this->fields;
    }
}
