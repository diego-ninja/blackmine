<?php

namespace Ninja\Redmine\Repository\Projects;

use Ninja\Redmine\Model\Project\TimeEntry;
use Ninja\Redmine\Repository\AbstractRepository;

class TimeEntries extends AbstractRepository
{

    protected function getModelClass(): string
    {
        return TimeEntry::class;
    }
}