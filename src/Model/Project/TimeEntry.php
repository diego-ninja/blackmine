<?php

declare(strict_types=1);

namespace Blackmine\Model\Project;

use Blackmine\Model\Enumeration\TimeEntryActivity;
use Carbon\CarbonImmutable;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Identity;
use Blackmine\Model\Issue\Issue;
use Blackmine\Model\User\User;
use Blackmine\Repository\Projects\TimeEntries;

/**
 * @method setIssue(Issue $issue): void
 * @method setProject(Project $project): void
 * @method setSpentOn(CarbonImmutable $spent_on): void
 * @method setHours(float $hours): void
 * @method setActivity(TimeEntryActivity $activity): void
 * @method setComments(string $comments): void
 * @method setUser(User $user): void
 *
 * @method Issue getIssue()
 * @method Project getProject()
 * @method CarbonImmutable getSpentOn()
 * @method TimeEntryActivity getActivity()
 * @method string getComments()
 * @method User getUser()
 */
class TimeEntry extends Identity implements FetchableInterface
{
    public const ENTITY_NAME = "time_entry";

    protected ?Issue $issue;
    protected ?Project $project;
    protected CarbonImmutable $spent_on;
    protected float $hours;
    protected TimeEntryActivity $activity;
    protected string $comments;
    protected User $user;

    public static function getRepositoryClass(): ?string
    {
        return TimeEntries::class;
    }
}
