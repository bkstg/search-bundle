<?php

namespace Bkstg\SearchBundle\Metadata;

class ClassMetadata implements ClassMetadataInterface
{
    private $class;
    private $field_metadata;

    public function __construct(string $class)
    {
        $this->class = $class;
        $this->field_metadata = [];
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setFieldMetadata(string $field, FieldMetadataInterface $metadata)
    {
        $this->field_metadata[$field] = $metadata;
    }

    public function createFieldMetadata(array $metadata) {
        foreach ($metadata as $field => $raw_field_metadata) {
            $field_metadata = new FieldMetadata($field);
            $field_metadata->setBoost($raw_field_metadata['boost'] ?: 0.0);
            $this->setFieldMetadata($field, $field_metadata);
        }
    }

    public function getAllFieldMetadata(): array
    {
        return $this->field_metadata;
    }

    public function getFieldMetadata(string $field): ?FieldMetadataInterface
    {
        return $this->field_metadata[$field] ?: null;
    }

    public static function createClassMetadata(string $class, array $metadata)
    {
        $class_metadata = new ClassMetadata($class);
        $class_metadata->createFieldMetadata($metadata);
        return $class_metadata;
    }
}
