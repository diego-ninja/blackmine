<?php

declare(strict_types=1);

namespace {
    /**
     * @throws ReflectionException
     */
    function is_initialized(object $obj, string $property): bool
    {
        $rp = new ReflectionProperty(get_class($obj), $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($obj);
    }
}