<?php

namespace Ninja\Redmine\Model;

class Identity extends AbstractModel
{
    protected int $id;
    protected ?string $name;

    public function getRepositoryClass(): ?string
    {
        return null;
    }

}