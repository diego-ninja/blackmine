<?php

namespace Blackmine\Collection;

class RepeatableNameCollection extends IdentityCollection
{
    public function toArray(): array
    {
        $ret = [];
        $elements = parent::toArray(); // TODO: Change the autogenerated stub
        foreach ($elements as $element) {
            $ret[] = $element["name"];
        }

        return $ret;

    }
}