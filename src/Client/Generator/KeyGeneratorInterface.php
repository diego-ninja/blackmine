<?php

namespace Blackmine\Client\Generator;

interface KeyGeneratorInterface
{
    public function generate(...$params): string;
}
