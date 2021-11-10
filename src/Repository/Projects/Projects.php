<?php

declare(strict_types=1);

namespace Blackmine\Repository\Projects;

use Blackmine\Collection\HierarchyCollection;
use Blackmine\Exception\Api\AbstractApiException;
use Blackmine\Exception\InvalidModelException;
use Blackmine\Model\News;
use Blackmine\Model\Project\File;
use Blackmine\Model\Project\IssueCategory;
use Blackmine\Model\Project\Module;
use Blackmine\Model\Project\Project;
use Blackmine\Model\Project\TimeEntry;
use Blackmine\Model\Project\Tracker;
use Blackmine\Model\Project\Version;
use Blackmine\Model\Project\WikiPage;
use Blackmine\Model\User\Membership;
use Blackmine\Repository\AbstractRepository;
use Blackmine\Repository\Uploads;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonException;

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
    public const PROJECT_RELATION_NEWS = "news";
    public const PROJECT_RELATION_WIKI_PAGES = "wiki_pages";

    protected static array $relation_class_map = [
        self::PROJECT_RELATION_TRACKERS => Tracker::class,
        self::PROJECT_RELATION_ISSUE_CATEGORIES => IssueCategory::class,
        self::PROJECT_RELATION_ENABLED_MODULES => Module::class,
        self::PROJECT_RELATION_TIME_ENTRIES => TimeEntry::class,
        self::PROJECT_RELATION_MEMBERSHIPS => Membership::class,
        self::PROJECT_RELATION_VERSIONS => Version::class,
        self::PROJECT_RELATION_FILES => File::class,
        self::PROJECT_RELATION_NEWS => News::class,
        self::PROJECT_RELATION_WIKI_PAGES => WikiPage::class
    ];

    protected static array $allowed_filters = [];

    public function getModelClass(): string
    {
        return Project::class;
    }

    public function getTimeEntries(Project $project): ArrayCollection
    {
        return $this->client->getRepository(TimeEntries::API_ROOT)
            ->addFilter(TimeEntries::TIME_ENTRY_FILTER_PROJECT_ID, $project->getId())
            ->search();
    }

    /**
     * @throws AbstractApiException
     * @throws InvalidModelException
     * @throws JsonException
     */
    public function addTimeEntry(Project $project, TimeEntry $time_entry): Project
    {
        $time_entry->setProject($project);
        $this->client->getRepository(TimeEntries::API_ROOT)?->create($time_entry);

        return $project;
    }

    /**
     * @throws AbstractApiException
     * @throws JsonException
     */
    public function getWikiPages(Project $project): Collection
    {
        /** @var WikiPages $wiki_pages */
        $wiki_pages = $this->client->getRepository(WikiPages::API_ROOT);
        $wiki_pages->setProject($project);

        return $wiki_pages->all();
    }

    public function addWikiPage(Project $project, WikiPage $wiki_page): Project
    {
        return $project;
    }


    /**
     * @throws AbstractApiException
     * @throws JsonException
     */
    protected function getWikiPage(Project $project, string $title, ?int $version = null): WikiPage
    {
        $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/wiki/" . $title . "." . $this->client->getFormat() . "?include=attachments";
        $response = $this->client->get($endpoint);

        if ($response->isSuccess()) {
            return (new WikiPage())->fromArray($response->getData()["wiki_page"]);
        }

        throw AbstractApiException::fromApiResponse($response);
    }

    /**
     * @throws AbstractApiException
     * @throws InvalidModelException
     * @throws JsonException
     */
    public function addFile(Project $project, File $file): Project
    {
        $file = $this->client->getRepository(Uploads::API_ROOT)?->create($file);
        /** @var File $file */
        if ($file !== null) {
            $file->setVersion($project->getDefaultVersion());

            $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/files." . $this->client->getFormat();
            $api_response = $this->client->post($endpoint, json_encode($file->getPayload(), JSON_THROW_ON_ERROR));

            if ($api_response->isSuccess()) {
                $project->addFile($file);
            } else {
                throw AbstractApiException::fromApiResponse($api_response);
            }
        }

        return $project;
    }

    /**
     * @throws AbstractApiException
     * @throws JsonException
     */
    public function archive(Project $project): Project
    {
        $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/archive" . "." . $this->client->getFormat();
        $api_response = $this->client->put($endpoint, '', ["Content-Length" => 0]);

        if (!$api_response->isSuccess()) {
            throw AbstractApiException::fromApiResponse($api_response);
        }

        return $project;
    }

    /**
     * @throws AbstractApiException
     * @throws JsonException
     */
    public function unArchive(Project $project): Project
    {
        $endpoint = $this->getEndpoint() . "/" . $project->getId() . "/unarchive" . "." . $this->client->getFormat();
        $api_response = $this->client->put($endpoint, '', ["Content-Length" => 0]);

        if (!$api_response->isSuccess()) {
            throw AbstractApiException::fromApiResponse($api_response);
        }

        return $project;
    }
}
