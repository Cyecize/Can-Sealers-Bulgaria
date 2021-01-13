<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 11:58 AM
 */

namespace App\Utils;

class ModelMapper
{

    public function map($sourceInstance, $destinationClass)
    {
        return $this->merge($sourceInstance, new $destinationClass, true);
    }

    /**
     * @param $sourceInstance
     * @param $destinationInstance
     * @param bool $skipNull
     * @return mixed
     */
    public function merge($sourceInstance, $destinationInstance, bool $skipNull = false)
    {
        $reflOfDestination = new \ReflectionObject($destinationInstance);
        $reflOfSource = new \ReflectionObject($sourceInstance);

        foreach ($reflOfSource->getProperties() as $sourceProperty) {
            $sourceVal = $this->getValue($reflOfSource, $sourceInstance, $sourceProperty);
            if (!$skipNull && $sourceVal == null) {
                continue;
            }

            $this->setValue($reflOfDestination, $destinationInstance, $sourceVal, $sourceProperty->getName());
        }

        return $destinationInstance;
    }

    private function getValue(\ReflectionObject $reflectionObject, $instance, \ReflectionProperty $property)
    {
        $propName = $property->getName();

        foreach ($reflectionObject->getMethods() as $method) {
            if ($method->getNumberOfParameters() == 0
                && (strtolower($method->getName()) == strtolower("is" . $propName)
                    || strtolower($method->getName()) == strtolower("get" . $propName))) {
                $method->setAccessible(true);
                return $method->invoke($instance);
            }
        }

        return $property->getValue($instance);
    }

    private function setValue(\ReflectionObject $reflectionObject,
                              $instance,
                              $value,
                              string $propertyName): bool
    {
        foreach ($reflectionObject->getMethods() as $method) {
            if ($method->getNumberOfParameters() == 1
                && strtolower($method->getName()) == strtolower("set" . $propertyName)) {
                $method->setAccessible(true);
                $method->invoke($instance, $value);

                return true;
            }
        }

        if (!$reflectionObject->hasProperty($propertyName)) {
            return false;
        }

        $prop = $reflectionObject->getProperty($propertyName);
        $prop->setAccessible(true);

        $prop->setValue($instance, $value);

        return true;
    }
}