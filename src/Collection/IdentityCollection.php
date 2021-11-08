<?php

declare(strict_types=1);

namespace Blackmine\Collection;

use Blackmine\Model\Identity;
use Blackmine\Model\NamedIdentity;
use Blackmine\Tool\Inflect;
use Doctrine\Common\Collections\ArrayCollection;

class IdentityCollection extends ArrayCollection
{
    public function toArray(): array
    {
        $ret = [];

        $elements =  parent::toArray();
        foreach ($elements as $identity) {
            $ret[] = $identity->toArray();
        }

        return $ret;
    }

    public function find(Identity $identity): ?Identity
    {
        if ($this->isEmpty()) {
            return null;
        }

        if (!$identity->isPersisted()) {
            return null;
        }


        if ($identity instanceof NamedIdentity) {
            return $this->findByName($identity->getName());
        }

        return $this->findById($identity->getId());
    }

    public function findByName(string $name): ?Identity
    {
        foreach ($this->getElements() as $identity) {
            if ($identity->getName() === $name) {
                return $identity;
            }
        }

        return null;
    }

    public function findById(int $id): ?Identity
    {
        foreach ($this->getElements() as $identity) {
            if ($identity->getId() === $id) {
                return $identity;
            }
        }

        return null;
    }

    public function getElements(): array
    {
        return parent::toArray();
    }

}