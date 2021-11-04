<?php

declare(strict_types=1);

namespace Blackmine\Model\Issue;

use Blackmine\Model\NamedIdentity;
use Blackmine\Repository\Issues\Statuses;

class Status extends NamedIdentity
{
    public const ENTITY_NAME = "issue_status";

    protected bool $is_closed;

    public static function getRepositoryClass(): ?string
    {
        return Statuses::class;
    }


}