<?php

namespace Dentaku\Redmine\Model\Issue;

use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Repository\Issues\Issues;

class Relation extends AbstractModel
{
    public const ENTITY_NAME = "relation";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}