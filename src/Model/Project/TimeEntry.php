<?php

namespace Ninja\Redmine\Model\Project;

use Ninja\Redmine\Model\Identity;
use Ninja\Redmine\Repository\Projects\TimeEntries;

class TimeEntry extends Identity
{
    public function getRepositoryClass(): ?string
    {
        return TimeEntries::class;
    }
}