<?php

namespace Ninja\Redmine\Repository\Projects;

use Ninja\Redmine\Model\Project\IssueCategory;
use Ninja\Redmine\Model\Project\Module;
use Ninja\Redmine\Model\Project\Project;
use Ninja\Redmine\Model\Project\TimeEntry;
use Ninja\Redmine\Model\Project\Tracker;
use Ninja\Redmine\Repository\AbstractRepository;

class Projects extends AbstractRepository
{
    public const API_ENDPOINT = "projects";

    public const PROJECT_RELATION_TRACKERS = "trackers";
    public const PROJECT_RELATION_ISSUE_CATEGORIES = "issue_categories";
    public const PROJECT_RELATION_ENABLED_MODULES = "enabled_modules";
    public const PROJECT_RELATION_TIME_ENTRY_ACTIVITIES = "time_entry_activities";

    protected static array $relation_class_map = [
        self::PROJECT_RELATION_TRACKERS => Tracker::class,
        self::PROJECT_RELATION_ISSUE_CATEGORIES => IssueCategory::class,
        self::PROJECT_RELATION_ENABLED_MODULES => Module::class,
        self::PROJECT_RELATION_TIME_ENTRY_ACTIVITIES => TimeEntry::class
    ];

    protected static array $allowed_filters = [];

    protected function getModelClass(): string
    {
        return Project::class;
    }

    public function archive(Project $project): Project
    {
        $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/archive" . "." . $this->getFormat();
        $data = $this->client->put($endpoint,'', ["Content-Length" => 0]);

        return $project;
    }

    public function unArchive(Project $project): Project
    {
        $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/unarchive" . "." . $this->getFormat();
        $data = $this->client->put($endpoint,'', ["Content-Length" => 0]);

        return $project;
    }

}