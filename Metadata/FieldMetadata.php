<?php

namespace Bkstg\SearchBundle\Metadata;

class FieldMetadata implements FieldMetadataInterface
{
    private $name;
    private $boost;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setBoost(float $boost)
    {
        $this->boost = $boost;
    }

    public function getBoost(): float
    {
        return $this->boost;
    }
}
