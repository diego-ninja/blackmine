<?php

namespace Ninja\Redmine\Model\Issue;

use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Ninja\Redmine\Model\Project\IssueCategory;
use Ninja\Redmine\Model\Project\Project;
use Ninja\Redmine\Model\Project\Tracker;
use Ninja\Redmine\Repository\Issues\Issues;
use Ninja\Redmine\Model\AbstractModel;
use Ninja\Redmine\Model\Identity;

class Issue extends AbstractModel
{
    public const ENTITY_NAME = "issue";

    protected int $id;
    protected Project $project;
    protected Tracker $tracker;
    protected Identity $status;
    protected Identity $priority;
    protected Identity $author;
    protected Assignee $assigned_to;
    protected IssueCategory $category;
    protected string $subject;
    protected string $description;
    protected CarbonImmutable $start_date;
    protected CarbonImmutable $due_date;
    protected float $done_ratio;
    protected float $estimated_hours;
    protected array $custom_fields;
    protected ?string $notes = null;

    protected ?Issue $parent = null;

    protected ?ArrayCollection $children;
    protected ?ArrayCollection $attachments;
    protected ?ArrayCollection $relations;
    protected ?ArrayCollection $journals;
    protected ?ArrayCollection $changesets;
    protected ?ArrayCollection $watchers;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;

    public function comment(string $note) {
        $this->notes = $note;
    }

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }
}