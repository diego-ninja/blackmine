<?php

namespace Dentaku\Redmine\Repository\Issues;

use Dentaku\Redmine\Model\Issue\Relation;
use Dentaku\Redmine\Repository\AbstractRepository;

class Relations extends AbstractRepository
{
    public const API_ROOT = "relations";

    protected function getModelClass(): string
    {
        return Relation::class;
    }
}