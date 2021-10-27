<?php

namespace Dentaku\Redmine\Repository\Projects;

use Dentaku\Redmine\Model\Project\TimeEntry;
use Dentaku\Redmine\Repository\AbstractRepository;
use Dentaku\Redmine\Repository\RepositoryInterface;

class TimeEntries extends AbstractRepository
{
    public const API_ROOT = "time_entries";

    public const TIME_ENTRY_FILTER_PROJECT_ID = "project_id";
    public const TIME_ENTRY_FILTER_USER_ID = "user_id";
    public const TIME_ENTRY_FILTER_SPENT_ON = "spent_on";

    protected static array $allowed_filters = [
        self::TIME_ENTRY_FILTER_SPENT_ON => RepositoryInterface::SEARCH_PARAM_TYPE_DATES_ARRAY,
        self::TIME_ENTRY_FILTER_USER_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::TIME_ENTRY_FILTER_PROJECT_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT
    ];

    protected function getModelClass(): string
    {
        return TimeEntry::class;
    }
}