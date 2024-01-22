<?php

namespace App\Helpers;

use ReflectionClass;
use ReflectionMethod;

class Utility
{
    /**
     * Get a list of available functions for a given status.
     *
     */
    public static function getAvailableFunctions($status): array
    {
        $availableFunctions = [];

        $reflectionClass = new ReflectionClass($status);

        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if (
                !$method->isConstructor() &&
                !$method->isDestructor() &&
                $method->class === get_class($status)
            ) {
                $availableFunctions[] = $method->getName();
            }
        }

        return $availableFunctions;
    }
}
