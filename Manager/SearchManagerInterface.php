<?php

namespace Bkstg\SearchBundle\Manager;

interface SearchManagerInterface
{
    public function buildQuery(string $query);
    public function execute(string $query);
}
