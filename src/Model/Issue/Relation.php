<?php

namespace Ninja\Redmine\Model\Issue;

use Ninja\Redmine\Model\AbstractModel;
use Ninja\Redmine\Repository\Issues\Issues;

class Relation extends AbstractModel
{
    public const ENTITY_NAME = "relation";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}