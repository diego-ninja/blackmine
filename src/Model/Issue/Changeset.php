<?php

declare(strict_types=1);

namespace Blackmine\Model\Issue;

use Blackmine\Model\AbstractModel;
use Blackmine\Repository\Issues\Issues;

class Changeset extends AbstractModel
{
    public const ENTITY_NAME = "changeset";

    public static function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}