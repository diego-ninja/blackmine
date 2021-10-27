<?php

namespace Dentaku\Redmine\Repository\Projects;

use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Model\Project\File;
use Dentaku\Redmine\Model\Project\IssueCategory;
use Dentaku\Redmine\Model\Project\Module;
use Dentaku\Redmine\Model\Project\Project;
use Dentaku\Redmine\Model\Project\TimeEntry;
use Dentaku\Redmine\Model\Project\Tracker;
use Dentaku\Redmine\Model\Project\Version;
use Dentaku\Redmine\Model\User\Membership;
use Dentaku\Redmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

class Projects extends AbstractRepository
{
    public const API_ROOT = "projects";

    public const PROJECT_RELATION_TRACKERS = "trackers";
    public const PROJECT_RELATION_ISSUE_CATEGORIES = "issue_categories";
    public const PROJECT_RELATION_ENABLED_MODULES = "enabled_modules";
    public const PROJECT_RELATION_TIME_ENTRY_ACTIVITIES = "time_entry_activities";
    public const PROJECT_RELATION_TIME_ENTRIES = "time_entries";
    public const PROJECT_RELATION_MEMBERSHIPS = "memberships";
    public const PROJECT_RELATION_VERSIONS = "versions";
    public const PROJECT_RELATION_FILES = "files";

    protected static array $relation_class_map = [
        self::PROJECT_RELATION_TRACKERS => Tracker::class,
        self::PROJECT_RELATION_ISSUE_CATEGORIES => IssueCategory::class,
        self::PROJECT_RELATION_ENABLED_MODULES => Module::class,
        self::PROJECT_RELATION_TIME_ENTRIES => TimeEntry::class,
        self::PROJECT_RELATION_TIME_ENTRY_ACTIVITIES => Identity::class,
        self::PROJECT_RELATION_MEMBERSHIPS => Membership::class,
        self::PROJECT_RELATION_VERSIONS => Version::class,
        self::PROJECT_RELATION_FILES => File::class
    ];

    protected static array $allowed_filters = [];

    protected function getModelClass(): string
    {
        return Project::class;
    }

    public function getTimeEntries(Project $project): ArrayCollection
    {
        return $this->client->getRepository(TimeEntries::API_ROOT)
            ->addFilter(TimeEntries::TIME_ENTRY_FILTER_PROJECT_ID, $project->getId())
            ->search();
    }

    public function addTimeEntry(Project $project, TimeEntry $time_entry): Project
    {
        $time_entry->setProject($project);
        $time_entry = $this->client->getRepository(TimeEntries::API_ROOT)->create($time_entry);

        return $project;
    }

    public function archive(Project $project): Project
    {
        $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/archive" . "." . $this->client->getFormat();
        $data = $this->client->put($endpoint,'', ["Content-Length" => 0]);

        return $project;
    }

    public function unArchive(Project $project): Project
    {
        $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/unarchive" . "." . $this->client->getFormat();
        $data = $this->client->put($endpoint,'', ["Content-Length" => 0]);

        return $project;
    }

}