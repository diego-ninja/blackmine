<?php

namespace Blackmine\Mutator\Mutation;

class RenameKeyMutation extends AbstractMutation
{
    public function __invoke(array $target, array ...$args): array
    {
        [$old_key, $new_key] = $args[0];

        if (isset($target[$old_key])) {
            $target[$new_key] = $target[$old_key];
            unset($target[$old_key]);
        }

        return $target;
    }
}