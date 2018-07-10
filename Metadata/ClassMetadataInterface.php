<?php

namespace Bkstg\SearchBundle\Metadata;

interface ClassMetadataInterface
{
    public function getClass(): string;
    public function getFieldMetadata(string $field): ?FieldMetadataInterface;
    public function getAllFieldMetadata(): array;
}
