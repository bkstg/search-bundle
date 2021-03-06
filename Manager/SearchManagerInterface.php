<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Manager;

use Elastica\Query;

interface SearchManagerInterface
{
    public function buildQuery(string $query);

    public function execute(Query $query);

    public function getResults();
}
