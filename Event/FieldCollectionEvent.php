<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function addFields(array $fields): void
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
