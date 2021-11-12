<?php

declare(strict_types=1);

namespace Blackmine\Model\Issue;

use Blackmine\Model\NamedIdentity;
use Blackmine\Repository\Issues\Statuses;

/**
 * @method void setIsClosed(bool $is_closed)
 * @method bool isClosed()
 */
class Status extends NamedIdentity
{
    public const ENTITY_NAME = "issue_status";

    protected bool $is_closed;

    public static function getRepositoryClass(): ?string
    {
        return Statuses::class;
    }

    public function close(): void
    {
        $this->is_closed = true;
    }
}
