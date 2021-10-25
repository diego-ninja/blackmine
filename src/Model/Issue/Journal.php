<?php

namespace Dentaku\Redmine\Model\Issue;

use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Repository\Issues\Issues;

class Journal extends AbstractModel
{
    public const ENTITY_NAME = "journal";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}