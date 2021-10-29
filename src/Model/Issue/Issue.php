<?php

namespace Blackmine\Model\Issue;

use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\Project\Version;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RemoveKeyMutation;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Carbon\CarbonImmutable;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\NamedIdentity;
use Blackmine\Model\User\User;
use Blackmine\Model\Project\IssueCategory;
use Blackmine\Model\Project\Project;
use Blackmine\Model\Project\Tracker;
use Blackmine\Repository\Issues\Issues;
use Blackmine\Model\Identity;

/**
 * @method void setProject(Project $project)
 * @method void setTracker(Tracker $tracker)
 * @method void setStatus (Status $status)
 * @method void setPriority(NamedIdentity $priority)
 * @method void setAuthor(User $author)
 * @method void setAssignedTo(User $user)
 * @method void setCategory(IssueCategory $category)
 * @method void setFixedVersion(Version $version)
 * @method void setSubject(string $subject)
 * @method void setDescription(string $description)
 * @method void setStartDate(CarbonImmutable $start_date)
 * @method void setDueDate(CarbonImmutable $due_date)
 * @method void setDoneRatio(float $done_ratio)
 * @method void setEstimatedHours(float $estimated_hours)
 * @method void setSpentHours(float $spent_hours)
 * @method void setNotes(string $notes)
 * @method void setPrivateNotes(bool $private_notes)
 * @method void setParentIssue(Issue $parent_issue)
 *
 * @method Project getProject()
 * @method Tracker getTracker()
 * @method Status getStatus()
 * @method NamedIdentity getPriority()
 * @method User getAuthor()
 * @method User getAssignedTo()
 * @method IssueCategory getCategory()
 * @method Version getFixedVersion()
 * @method string getSubject()
 * @method string getDescription()
 * @method CarbonImmutable getStartDate()
 * @method CarbonImmutable getDueDate()
 * @method float getDoneRatio()
 * @method float getEstimatedHours()
 * @method float getTotalEstimatedHours()
 * @method float getSpentHours()
 * @method float getTotalSpentHours()
 * @method Issue getParentIssue()
 * @method IdentityCollection getCustomFields()
 * @method IdentityCollection getRelations()
 * @method IdentityCollection getChildren()
 * @method IdentityCollection getAttachments()
 * @method IdentityCollection getJournals()
 * @method IdentityCollection getChangesets()
 * @method RepeatableIdCollection getWatchers()
 * @method CarbonImmutable getCreatedOn()
 * @method CarbonImmutable getUpdatedOn()
 * @method CarbonImmutable getClosedOn()
 *
 * @method void addWatcher(User $watcher)
 * @method void removeWatcher(User $watcher)
 * @method void addAttachment(Attachment $attachment)
 * @method void removeAttachment(Attachment $attachment)
 * @method void addChild(Issue $child);
 * @method void removeChild(Issue $child)
 *
 */
class Issue extends Identity implements FetchableInterface, MutableInterface
{
    public const ENTITY_NAME = "issue";

    protected Project $project;
    protected Tracker $tracker;
    protected Status $status;
    protected NamedIdentity $priority;
    protected User $author;
    protected User $assigned_to;
    protected IssueCategory $category;
    protected Version $fixed_version;
    protected string $subject;
    protected string $description;
    protected CarbonImmutable $start_date;
    protected CarbonImmutable $due_date;

    protected float $done_ratio;
    protected float $estimated_hours;
    protected float $total_estimated_hours;
    protected float $spent_hours;
    protected float $total_spent_hours;

    protected ?string $notes = null;
    protected bool $private_notes;

    protected ?Issue $parent_issue = null;

    protected ?IdentityCollection $custom_fields;
    protected ?IdentityCollection $relations;
    protected ?IdentityCollection $children;
    protected ?IdentityCollection $attachments;
    protected ?IdentityCollection $journals;
    protected ?IdentityCollection $changesets;
    protected ?RepeatableIdCollection $watchers;

    protected CarbonImmutable $created_on;
    protected ?CarbonImmutable $updated_on;
    protected ?CarbonImmutable $closed_on;


    public function __construct(protected ?int $id = null)
    {
        $this->attachments = new IdentityCollection();
        $this->children = new IdentityCollection();
        $this->custom_fields = new IdentityCollection();
    }

    public function comment(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

    public function getMutations(): array
    {
        return [
            "parent_issue" => [RenameKeyMutation::class => ["parent_issue_id"]],
            "watchers" => [RenameKeyMutation::class => ["watcher_user_ids"]],
            "attachments" => [RenameKeyMutation::class => ["uploads"]],
            "created_on" => [RemoveKeyMutation::class => []],
            "updated_on" => [RemoveKeyMutation::class  => []],
            "closed_on" => [RemoveKeyMutation::class  => []],
            "changesets" => [RemoveKeyMutation::class  => []],
            "relations" => [RemoveKeyMutation::class  => []],
            "journals" => [RemoveKeyMutation::class  => []],
        ];
    }
}