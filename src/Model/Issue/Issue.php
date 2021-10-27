<?php

namespace Dentaku\Redmine\Model\Issue;

use Carbon\CarbonImmutable;
use Dentaku\Redmine\Collection\IdentityCollection;
use Dentaku\Redmine\Model\FetchableInterface;
use Dentaku\Redmine\Model\NamedIdentity;
use Dentaku\Redmine\Model\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Dentaku\Redmine\Model\Project\IssueCategory;
use Dentaku\Redmine\Model\Project\Project;
use Dentaku\Redmine\Model\Project\Tracker;
use Dentaku\Redmine\Repository\Issues\Issues;
use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Model\Identity;

class Issue extends Identity implements FetchableInterface
{
    public const ENTITY_NAME = "issue";

    protected Project $project;
    protected Tracker $tracker;
    protected Status $status;
    protected NamedIdentity $priority;
    protected NamedIdentity $author;
    protected Assignee $assigned_to;
    protected IssueCategory $category;
    protected string $subject;
    protected string $description;
    protected CarbonImmutable $start_date;
    protected CarbonImmutable $due_date;
    protected float $done_ratio;
    protected float $estimated_hours;
    protected ?string $notes = null;

    protected ?Issue $parent = null;

    protected ?IdentityCollection $custom_fields;
    protected ?IdentityCollection $relations;

    protected ?ArrayCollection $children;
    protected ?ArrayCollection $attachments;
    protected ?ArrayCollection $journals;
    protected ?ArrayCollection $changesets;
    protected ?ArrayCollection $watchers;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;

    protected static array $payload_mutations = [
        "parent" => "parent_issue_id"
    ];

    public function comment(string $note) {
        $this->notes = $note;
    }

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }
}