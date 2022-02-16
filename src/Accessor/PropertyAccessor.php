<?php

namespace Indigerd\Hydrator\Accessor;

use ReflectionClass;

class PropertyAccessor implements AccessorInterface
{
    public function set(object $object, ReflectionClass $reflection, $name, $value)
    {
        try {
            $property = $reflection->getProperty($name);
            if ($property->isPrivate() || $property->isProtected()) {
                $property->setAccessible(true);
            }
            $property->setValue($object, $value);
        } catch (\ReflectionException $e) {}
    }

    public function get(object $object, ReflectionClass $reflection, $name)
    {
        try {
            $property = $reflection->getProperty($name);
            if ($property->isPrivate() || $property->isProtected()) {
                $property->setAccessible(true);
            }
            
            if ($property->isInitialized($object)) {
                return $property->getValue($object);
            }

            return null;
        } catch (\ReflectionException $e) {}
    }

    public function getPropertyNames(object $object, ReflectionClass $reflection)
    {
        $properties = $reflection->getProperties();
        $result = [];
        foreach ($properties as $property) {
            $result[] = $property->getName();
        }
        return $result;
    }
}
