<?php

namespace Dentaku\Redmine\Repository\Issues;

use Carbon\CarbonInterface;
use Dentaku\Redmine\Model\User\Watcher;
use Dentaku\Redmine\Repository\AbstractRepository;
use Dentaku\Redmine\Model\Issue\Attachment;
use Dentaku\Redmine\Model\Issue\Changeset;
use Dentaku\Redmine\Model\Issue\Issue;
use Dentaku\Redmine\Model\Issue\Journal;
use Dentaku\Redmine\Model\Issue\Relation;
use Dentaku\Redmine\Repository\RepositoryInterface;

class Issues extends AbstractRepository
{
    public const API_ENDPOINT = "issues";

    public const ISSUE_RELATION_CHILDREN = "children";
    public const ISSUE_RELATION_ATTACHMENTS = "attachments";
    public const ISSUE_RELATION_RELATIONS = "relations";
    public const ISSUE_RELATION_JOURNALS = "journals";
    public const ISSUE_RELATION_CHANGESETS = "changesets";
    public const ISSUE_RELATION_WATCHERS = "watchers";

    public const ISSUE_FILTER_ISSUE_ID = "issue_id";
    public const ISSUE_FILTER_PARENT_ID = "parent_id";
    public const ISSUE_FILTER_PROJECT_ID = "project_id";
    public const ISSUE_FILTER_SUBPROJECT_ID = "subproject_id";
    public const ISSUE_FILTER_TRACKER_ID = "tracker_id";
    public const ISSUE_FILTER_STATUS_ID = "status_id";
    public const ISSUE_FILTER_ASSIGNED_TO_ID = "assigned_to_id";


    protected static array $relation_class_map = [
        self::ISSUE_RELATION_CHILDREN => Issue::class,
        self::ISSUE_RELATION_ATTACHMENTS => Attachment::class,
        self::ISSUE_RELATION_RELATIONS => Relation::class,
        self::ISSUE_RELATION_JOURNALS => Journal::class,
        self::ISSUE_RELATION_CHANGESETS => Changeset::class,
        self::ISSUE_RELATION_WATCHERS => Watcher::class
    ];

    protected static array $allowed_filters = [
        self::ISSUE_FILTER_ISSUE_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT_ARRAY,
        self::ISSUE_FILTER_PROJECT_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_SUBPROJECT_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_TRACKER_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_STATUS_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_PARENT_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_ASSIGNED_TO_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::COMMON_FILTER_CREATED_ON => CarbonInterface::class,
        self::COMMON_FILTER_UPDATED_ON => CarbonInterface::class,
        self::COMMON_FILTER_CUSTOM_FIELDS => RepositoryInterface::SEARCH_PARAM_TYPE_CF_ARRAY
    ];

    public function getModelClass(): string
    {
        return Issue::class;
    }

}