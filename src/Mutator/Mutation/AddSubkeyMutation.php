<?php

namespace Blackmine\Mutator\Mutation;

use Blackmine\Tool\Inflect;

class AddSubkeyMutation extends AbstractMutation
{

    public function __invoke(array $target, array ...$args): array
    {
        [$key, $subkey] = $args[0];

        if (isset($target[$key]) && is_array($target[$key])) {
            $target[$key][$subkey] = $target[$key];
            return $target;
        }

        if (is_object($target[$key])) {
            $getter = "get" . Inflect::camelize(ucfirst((string)$subkey));
            if (method_exists($target[$key], $getter)) {
                $target[$key][$subkey] = $target[$key]->$getter();
            }
            return $target;
        }

        if (isset($target[$key])) {
            $old_value = $target[$key];
            unset($target[$key]);

            $target[$key][$subkey] = $old_value;
            return $target;
        }

        return $target;
    }
}
