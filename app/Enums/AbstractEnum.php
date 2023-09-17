<?php
namespace App\Enums;
use ReflectionClass;
use InvalidArgumentException;
abstract class AbstractEnum
{
    private $value;
    public function __construct($value)
    {
        if ( ! in_array($value, $this->getValues()) ) {
            throw new InvalidArgumentException;
        }
        $this->value = $value;
    }
    public static function getConstants()
    {
        $reflectionClass = new ReflectionClass(get_called_class());
        return array_keys($reflectionClass->getConstants());
    }
    public static function getValues()
    {
        $reflectionClass = new ReflectionClass(get_called_class());
        return array_values($reflectionClass->getConstants());
    }
    public function getValue()
    {
        return $this->value;
    }
    public function __toString()
    {
        return $this->value;
    }
}
