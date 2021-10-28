<?php

namespace Blackmine\Model\Issue;

use Blackmine\Model\AbstractModel;
use Blackmine\Repository\Issues\Issues;

class Changeset extends AbstractModel
{
    public const ENTITY_NAME = "changeset";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}