<?php

namespace Dentaku\Redmine\Model\Project;

use Carbon\CarbonImmutable;
use Dentaku\Redmine\Collection\IdentityCollection;
use Dentaku\Redmine\Collection\RepeatableNameCollection;
use Dentaku\Redmine\Collection\RepeatableIdCollection;
use Dentaku\Redmine\Model\FetchableInterface;
use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Model\Issue\Assignee;
use Dentaku\Redmine\Model\Issue\Issue;
use Dentaku\Redmine\Model\Issue\Status;
use Dentaku\Redmine\Model\NamedIdentity;
use Dentaku\Redmine\Model\User\Membership;
use Dentaku\Redmine\Model\User\User;
use Dentaku\Redmine\Repository\Projects\Projects;

/**
 * @method setIdentifier(string $identifier): void
 * @method setDescription(string $description): void
 * @method setHomePage(string $homepage): void
 * @method setStatus(int $status): void
 * @method setParent(Project $parent): void
 * @method setDefaultVersion(Version $version): void
 * @method setDefaultAssignee(Assignee $assignee): void
 * @method setInheritMembers(bool $inherit_members): void
 * @method setIsPublic(bool $is_public): void
 *
 * @method addTracker(Tracker $tracker): void
 * @method removeTracker(Tracker $tracker): void
 * @method addEnabledModule(Module $module): void
 * @method removeEnabledModule(Module $module): void
 * @method addTimeEntry(TimeEntry $time_entry): void
 * @method removeTimeEntry(TimeEntry $time_entry): void
 * @method addIssueCategory(IssueCategory $issue_category): void
 * @method removeIssueCategory(IssueCategory $issue_category): void
 * @method addMembership(Membership $membership): void
 * @method removeMembership(Membership $membership): void
 * @method addVersion(Version $version): void
 * @method removeVersion(Version $version): void
 */
class Project extends NamedIdentity implements FetchableInterface
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
    protected IdentityCollection $time_entries;
    protected IdentityCollection $time_entry_activities;
    protected IdentityCollection $issue_categories;
    protected IdentityCollection $memberships;
    protected IdentityCollection $versions;
    protected IdentityCollection $files;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;

    protected static array $payload_mutations = [
        "trackers" => "tracker_ids",
        "enabled_modules" => "enabled_module_names",
        "default_assignee_id" => "default_assigned_to_id"
    ];

    public function addFile(string $filename, ?string $description) {

    }

    public function getRepositoryClass(): ?string
    {
        return Projects::class;
    }

    public function addUser(User $user, array $roles): void
    {
        $membership = $this->getUserMembership($user);
        if (!$membership) {
            $membership = new Membership();
            $membership->setUser($user);
            $membership->setRoles($roles);
            $membership->setProject($this);
        } else {
            foreach ($roles as $role) {
                $membership->addRole($role);
            }
        }

        $this->memberships->add($membership);
    }

    public function getUserMembership(User $user): ?Membership
    {
        foreach ($this->memberships as $membership) {
            if ($membership->getUser()->getId() === $user->getId()) {
                return $membership;
            }
        }

        return null;
    }

}