<?php

namespace Dentaku\Redmine\Repository\Projects;

use Dentaku\Redmine\Model\Project\TimeEntry;
use Dentaku\Redmine\Repository\AbstractRepository;

class TimeEntries extends AbstractRepository
{
    public const API_ROOT = "time_entries";

    protected function getModelClass(): string
    {
        return TimeEntry::class;
    }
}