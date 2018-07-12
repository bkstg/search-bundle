<?php

namespace Bkstg\SearchBundle\Manager;

use Elastica\Query;

interface SearchManagerInterface
{
    public function buildQuery(string $query);
    public function execute(Query $query);
    public function getResults();
}
