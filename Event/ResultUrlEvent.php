<?php

namespace Bkstg\SearchBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ResultUrlEvent extends Event
{
    const NAME = 'bkstg_search.result_url';

    private $entity;
    private $url;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }
}
