<?php

declare(strict_types=1);

namespace Blackmine\Factory;

use Blackmine\Model\NamedIdentity;

class NamedIdentityFactory implements FactoryInterface
{
    protected static array $values;
    protected static string $concrete_class;

    public function __construct(array $values, string $concrete_class = NamedIdentity::class)
    {
        self::$values = $values;
        self::$concrete_class = $concrete_class;
    }

    public static function make(int $id): ?NamedIdentity
    {
        foreach (self::$values as $value) {
            if ($value["id"] === $id) {
                return (new self::$concrete_class())->fromArray($value);
            }
        }

        return null;
    }

}