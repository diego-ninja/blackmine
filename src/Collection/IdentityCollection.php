<?php

namespace Ninja\Redmine\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class IdentityCollection extends ArrayCollection
{
    public function toArray(): array
    {
        $ret = [];

        $elements =  parent::toArray();
        foreach ($elements as $identity) {
            $ret[] = [
                "id" => $identity->getId(),
                "name" => $identity->getName()
            ];
        }

        return $ret;
    }
}