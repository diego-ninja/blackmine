<?php

namespace Blackmine\Model\Project;

use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Carbon\CarbonImmutable;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Collection\RepeatableNameCollection;
use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Issue\Assignee;
use Blackmine\Model\NamedIdentity;
use Blackmine\Model\User\Membership;
use Blackmine\Model\User\User;
use Blackmine\Repository\Projects\Projects;

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
 * @method Version getDefaultVersion()
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
class Project extends NamedIdentity implements FetchableInterface, MutableInterface
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

    public function getMutations(): array
    {
        return [
            "trackers" => [RenameKeyMutation::class => ["tracker_ids"]],
            "enabled_modules" => [RenameKeyMutation::class => ["enabled_modules"]],
            "default_assignee_id" => [RenameKeyMutation::class => ["default_assigned_to_id"]]
        ];
    }

}