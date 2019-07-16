<?php

namespace Indigerd\Hydrator\Strategy;

use Indigerd\Hydrator\Hydrator;

class CollectionStrategy implements StrategyInterface
{
    protected $hydrator;

    protected $entityName;

    public function __construct(Hydrator $hydrator, string $entityName)
    {
        $this->hydrator = $hydrator;
        $this->entityName = $entityName;
    }

    public function hydrate($value, ?array $data = null, $oldValue = null)
    {
        $value = (array)$value;
        return \array_map(function ($data) {
            return $this->hydrator->hydrate($this->entityName, $data);
        }, $value);
    }

    public function extract($value, ?object $object = null)
    {
        if (!\is_array($value)) {
            return [];
        }
        return \array_map(function ($object) {
            return $this->hydrator->extract($object);
        }, $value);
    }
}
