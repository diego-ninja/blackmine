<?php

declare(strict_types=1);

namespace Blackmine\Mutator\Mutation;

class RemoveKeyMutation extends AbstractMutation
{
    public function __invoke(array $target, array ...$args): array
    {
        [$key] = $args[0];

        if (isset($target[$key])) {
            unset($target[$key]);
        }

        return $target;
    }

}