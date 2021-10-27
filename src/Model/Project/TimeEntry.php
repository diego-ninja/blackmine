<?php

namespace Dentaku\Redmine\Model\Project;

use Dentaku\Redmine\Model\FetchableInterface;
use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Repository\Projects\TimeEntries;

class TimeEntry extends Identity implements FetchableInterface
{
    public function getRepositoryClass(): ?string
    {
        return TimeEntries::class;
    }
}