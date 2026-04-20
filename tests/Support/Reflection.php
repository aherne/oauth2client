<?php

namespace Test\Lucinda\OAuth2\Support;

class Reflection
{
    public static function get(object $object, string $property): mixed
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        return $reflectionProperty->getValue($object);
    }

    public static function set(object $object, string $property, mixed $value): void
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setValue($object, $value);
    }

    public static function newWithoutConstructor(string $className): object
    {
        return (new \ReflectionClass($className))->newInstanceWithoutConstructor();
    }
}
