<?php

namespace Indigerd\Hydrator\Strategy;

interface StrategyInterface
{
    public function extract($value, ?object $object = null);

    public function hydrate($value, ?array $data = null, $oldValue = null);
}
