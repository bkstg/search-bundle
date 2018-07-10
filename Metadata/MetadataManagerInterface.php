<?php

namespace Bkstg\SearchBundle\Metadata;

use Bkstg\SearchBundle\Metadata\ClassMetadataInterface;

interface MetadataManagerInterface
{
    public function getMetadata(string $class): ?ClassMetadataInterface;
    public function setMetadata(string $class, ClassMetadataInterface $metadata): void;
}
