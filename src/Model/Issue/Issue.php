<?php

namespace Blackmine\Model\Issue;

use Carbon\CarbonImmutable;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\NamedIdentity;
use Blackmine\Model\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Blackmine\Model\Project\IssueCategory;
use Blackmine\Model\Project\Project;
use Blackmine\Model\Project\Tracker;
use Blackmine\Repository\Issues\Issues;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Identity;

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