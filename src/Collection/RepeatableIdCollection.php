<?php

declare(strict_types=1);

namespace Blackmine\Collection;

class RepeatableIdCollection extends IdentityCollection
{
    public function toArray(): array
    {
        $ret = [];
        $elements = parent::toArray();
        foreach ($elements as $element) {
            $ret[] = $element["id"];
        }

        return $ret;
    }
}
