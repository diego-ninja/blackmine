<?php

namespace Dentaku\Redmine\Repository\Projects;

use Dentaku\Redmine\Model\Project\TimeEntry;
use Dentaku\Redmine\Repository\AbstractRepository;

class TimeEntries extends AbstractRepository
{

    protected function getModelClass(): string
    {
        return TimeEntry::class;
    }
}