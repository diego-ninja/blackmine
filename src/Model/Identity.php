<?php

namespace Dentaku\Redmine\Model;

class Identity extends AbstractModel
{
    public function __construct(protected ?int $id = null)
    {
    }

    public function getRepositoryClass(): ?string
    {
        return null;
    }

}