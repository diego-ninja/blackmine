<?php

namespace Ninja\Redmine\Repository\Issues;

use Ninja\Redmine\Model\User\Watcher;
use Ninja\Redmine\Repository\AbstractRepository;
use Ninja\Redmine\Model\Issue\Attachment;
use Ninja\Redmine\Model\Issue\Changeset;
use Ninja\Redmine\Model\Issue\Issue;
use Ninja\Redmine\Model\Issue\Journal;
use Ninja\Redmine\Model\Issue\Relation;

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
    public const ISSUE_FILTER_PROJECT_ID = "project_id";
    public const ISSUE_FILTER_SUBPROJECT_ID = "subproject_id";
    public const ISSUE_FILTER_TRACKER_ID = "tracker_id";
    public const ISSUE_FILTER_STATUS_ID = "status_id";

    protected static array $relation_class_map = [
        self::ISSUE_RELATION_CHILDREN => Issue::class,
        self::ISSUE_RELATION_ATTACHMENTS => Attachment::class,
        self::ISSUE_RELATION_RELATIONS => Relation::class,
        self::ISSUE_RELATION_JOURNALS => Journal::class,
        self::ISSUE_RELATION_CHANGESETS => Changeset::class,
        self::ISSUE_RELATION_WATCHERS => Watcher::class
    ];

    protected static array $allowed_filters = [
        self::ISSUE_FILTER_ISSUE_ID,
        self::ISSUE_FILTER_PROJECT_ID,
        self::ISSUE_FILTER_SUBPROJECT_ID,
        self::ISSUE_FILTER_TRACKER_ID,
        self::ISSUE_FILTER_STATUS_ID
    ];

    public function getModelClass(): string
    {
        return Issue::class;
    }
}