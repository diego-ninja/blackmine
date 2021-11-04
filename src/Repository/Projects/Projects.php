<?php

declare(strict_types=1);

namespace Blackmine\Repository\Projects;

use Blackmine\Model\Identity;
use Blackmine\Model\Project\File;
use Blackmine\Model\Project\IssueCategory;
use Blackmine\Model\Project\Module;
use Blackmine\Model\Project\Project;
use Blackmine\Model\Project\TimeEntry;
use Blackmine\Model\Project\Tracker;
use Blackmine\Model\Project\Version;
use Blackmine\Model\User\Membership;
use Blackmine\Repository\AbstractRepository;
use Blackmine\Repository\Uploads;
use Doctrine\Common\Collections\ArrayCollection;

class Projects extends AbstractRepository
{
    public const API_ROOT = "projects";

    public const PROJECT_RELATION_TRACKERS = "trackers";
    public const PROJECT_RELATION_ISSUE_CATEGORIES = "issue_categories";
    public const PROJECT_RELATION_ENABLED_MODULES = "enabled_modules";
    public const PROJECT_RELATION_TIME_ENTRIES = "time_entries";
    public const PROJECT_RELATION_MEMBERSHIPS = "memberships";
    public const PROJECT_RELATION_VERSIONS = "versions";
    public const PROJECT_RELATION_FILES = "files";

    protected static array $relation_class_map = [
        self::PROJECT_RELATION_TRACKERS => Tracker::class,
        self::PROJECT_RELATION_ISSUE_CATEGORIES => IssueCategory::class,
        self::PROJECT_RELATION_ENABLED_MODULES => Module::class,
        self::PROJECT_RELATION_TIME_ENTRIES => TimeEntry::class,
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

    public function addFile(Project $project, File $file): Project
    {
        $file = $this->client->getRepository(Uploads::API_ROOT)->create($file);
        if ($file) {
            $file->setVersion($project->getDefaultVersion());

            $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/files." . $this->client->getFormat();
            $response = $this->client->post($endpoint, json_encode($file->getPayload(), JSON_THROW_ON_ERROR));

            if ($response->isSuccess()) {
                $project->addFile($file);
            }
        }

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