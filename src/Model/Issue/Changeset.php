<?php

namespace Dentaku\Redmine\Model\Issue;

use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Repository\Issues\Issues;

class Changeset extends AbstractModel
{
    public const ENTITY_NAME = "changeset";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}