<?php

namespace Bkstg\SearchBundle\Metadata;

class MetadataManager implements MetadataManagerInterface
{
    private $metadata;

    public function __construct()
    {
        $this->metadata = [];
    }

    public function getMetadata(string $class): ?ClassMetadataInterface
    {
        return isset($this->metadata[$class]) ?: null;
    }

    public function setMetadata(string $class, ClassMetadataInterface $metadata): void
    {
        $this->metadata[$class] = $metadata;
    }
}
