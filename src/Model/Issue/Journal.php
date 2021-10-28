<?php

namespace Blackmine\Model\Issue;

use Blackmine\Model\AbstractModel;
use Blackmine\Repository\Issues\Issues;

class Journal extends AbstractModel
{
    public const ENTITY_NAME = "journal";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}