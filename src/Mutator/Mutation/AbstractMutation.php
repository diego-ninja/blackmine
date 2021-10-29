<?php

namespace Blackmine\Mutator\Mutation;

abstract class AbstractMutation
{
    abstract public function __invoke(array $target, array ...$args): array;
}