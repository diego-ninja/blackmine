<?php

namespace Dentaku\Redmine\Model\Project;

use Carbon\CarbonImmutable;
use Dentaku\Redmine\Collection\IdentityCollection;
use Dentaku\Redmine\Collection\RepeatableNameCollection;
use Dentaku\Redmine\Collection\RepeatableIdCollection;
use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Model\Issue\Assignee;
use Dentaku\Redmine\Repository\Projects\Projects;

class Project extends Identity
{
    public const ENTITY_NAME = "project";

    protected string $identifier;
    protected string $description;
    protected string $homepage;
    protected int $status;
    protected ?Project $parent;
    protected Version $default_version;
    protected Assignee $default_assignee;

    protected ?bool $inherit_members;
    protected ?bool $is_public;

    protected RepeatableIdCollection $trackers;
    protected RepeatableNameCollection $enabled_modules;
    protected IdentityCollection $time_entry_activities;
    protected IdentityCollection $issue_categories;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;

    protected static array $payload_mutations = [
        "trackers" => "tracker_ids",
        "enabled_modules" => "enabled_module_names",
        "default_assignee_id" => "default_assigned_to_id"
    ];

    public function getRepositoryClass(): ?string
    {
        return Projects::class;
    }
}