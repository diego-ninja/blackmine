<?php

namespace Blackmine\Model\Issue;

use Blackmine\Model\NamedIdentity;

class Status extends NamedIdentity
{
    public const ENTITY_NAME = "issue_status";

    protected bool $is_closed;

}