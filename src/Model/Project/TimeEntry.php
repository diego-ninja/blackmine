<?php

namespace Dentaku\Redmine\Model\Project;

use Carbon\CarbonImmutable;
use Dentaku\Redmine\Model\FetchableInterface;
use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Model\Issue\Issue;
use Dentaku\Redmine\Model\NamedIdentity;
use Dentaku\Redmine\Model\User\User;
use Dentaku\Redmine\Repository\Projects\TimeEntries;

/**
 * @method setIssue(Issue $issue): void
 * @method setProject(Project $project): void
 * @method setSpentOn(CarbonImmutable $spent_on): void
 * @method setHours(int $hours): void
 * @method setActivity(NamedIdentity $activity): void
 * @method setComments(string $comments): void
 * @method setUser(User $user): void
 *
 * @method Issue getIssue()
 * @method Project getProject()
 * @method CarbonImmutable getSpentOn()
 * @method NamedIdentity getActivity()
 * @method string getComments()
 * @method User getUser()
 */
class TimeEntry extends Identity implements FetchableInterface
{
    public const ENTITY_NAME = "time_entry";

    protected ?Issue $issue;
    protected ?Project $project;
    protected CarbonImmutable $spent_on;
    protected int $hours;
    protected NamedIdentity $activity;
    protected string $comments;
    protected User $user;

    public function getRepositoryClass(): ?string
    {
        return TimeEntries::class;
    }
}