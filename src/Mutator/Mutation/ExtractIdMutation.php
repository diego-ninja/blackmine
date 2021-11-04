<?php

declare(strict_types=1);

namespace Blackmine\Mutator\Mutation;

class ExtractIdMutation extends AbstractMutation
{

    public function __invoke(array $target, array ...$args): array
    {
        [$key] = $args[0];

        if (isset($target[$key]) && is_array($target[$key])) {
            if (isset($target[$key]["id"])) {
                $target[$key] = $target[$key]["id"];
            }

            if (is_object($target[$key]) && method_exists($target[$key], "getId")) {
                $target[$key] = $target[$key]->getId();
            }
        }

        return $target;

    }
}