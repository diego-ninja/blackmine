<?php

declare(strict_types=1);

namespace Blackmine\Factory;

use Blackmine\Model\ModelInterface;

interface FactoryInterface
{
    public static function make(int $id): ?ModelInterface;
}