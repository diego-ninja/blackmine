<?php

namespace Ninja\Redmine\Model\Issue;

use Ninja\Redmine\Model\AbstractModel;
use Ninja\Redmine\Repository\Issues\Issues;

class Changeset extends AbstractModel
{
    public const ENTITY_NAME = "changeset";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}