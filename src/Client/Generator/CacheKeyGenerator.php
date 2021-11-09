<?php

namespace Blackmine\Client\Generator;

class CacheKeyGenerator implements KeyGeneratorInterface
{

    protected const KEY_SEPARATOR = "";

    public function __construct(protected string $seed)
    {
    }

    public function generate(...$params): string
    {
        return strtoupper(sha1($this->seed . implode(static::KEY_SEPARATOR, $params)));
    }
}
