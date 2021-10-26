<?php

namespace Dentaku\Redmine\Model\Issue;

use Dentaku\Redmine\Model\NamedIdentity;

class Status extends NamedIdentity
{
    public const ENTITY_NAME = "issue_status";

    protected bool $is_closed;

}