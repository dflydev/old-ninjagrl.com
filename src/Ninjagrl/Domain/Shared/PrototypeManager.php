<?php

namespace Ninjagrl\Domain\Shared;

class PrototypeManager
{
    private $prototype;
    private $reflectionClass;

    public function __construct($class)
    {
        $this->reflectionClass = new \ReflectionClass($class);

        $isPhp54OrLater = version_compare(PHP_VERSION, '5.4.0', '>=');
        if ($isPhp54OrLater) {
            $this->prototype = $this->reflectionClass->newInstanceWithoutConstructor();
        } else {
            $this->prototype = unserialize('O:'.strlen($class).':"'.$class.'":0:{}');
        }
    }

    public function buildProperty($propertyName)
    {
        $property = $this->reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }

    public function createClone()
    {
        return clone($this->prototype);
    }
}
