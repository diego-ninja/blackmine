<?php

declare(strict_types=1);

namespace {
    function is_initialized(object $obj, string $property): bool
    {
        try {
            $rp = new ReflectionProperty(get_class($obj), $property);
            $rp->setAccessible(true);
            return $rp->isInitialized($obj);
        } catch (ReflectionException $e) {
            return false;
        }
    }

    function is_static(object $obj, string $property): bool
    {
        try {
            $rp = new ReflectionProperty(get_class($obj), $property);
            return $rp->isStatic();
        } catch (ReflectionException $e) {
            return false;
        }
    }

}