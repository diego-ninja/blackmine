<?php

namespace Ninja\Redmine\Model\Issue;

use Ninja\Redmine\Model\AbstractModel;
use Ninja\Redmine\Repository\Issues\Issues;

class Journal extends AbstractModel
{
    public const ENTITY_NAME = "journal";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}