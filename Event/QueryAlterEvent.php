<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Event;

use Elastica\Query;
use Symfony\Component\EventDispatcher\Event;

class QueryAlterEvent extends Event
{
    const NAME = 'bkstg_search.query_alter';

    private $query;

    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
