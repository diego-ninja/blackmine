<?php

namespace Blackmine\Repository\Issues;

use Blackmine\Model\Issue\Relation;
use Blackmine\Repository\AbstractRepository;

class Relations extends AbstractRepository
{
    public const API_ROOT = "relations";

    protected function getModelClass(): string
    {
        return Relation::class;
    }
}