<?php

declare(strict_types=1);

namespace Blackmine\Model\Project;

use Blackmine\Collection\PaginatedCollection;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Carbon\CarbonImmutable;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Collection\RepeatableNameCollection;
use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\NamedIdentity;
use Blackmine\Model\User\Membership;
use Blackmine\Model\User\User;
use Blackmine\Repository\Projects\Projects;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method setIdentifier(string $identifier): void
 * @method setDescription(string $description): void
 * @method setHomePage(string $homepage): void
 * @method setStatus(int $status): void
 * @method setParent(Project $parent): void
 * @method setDefaultVersion(Version $version): void
 * @method setDefaultAssignee(User $assignee): void
 * @method setInheritMembers(bool $inherit_members): void
 * @method setIsPublic(bool $is_public): void
 *
 * @method string getIdentifier()
 * @method string getDescription()
 * @method string getHomepage()
 * @method int getStatus()
 * @method Project|null getParent()
 * @method Version|null getDefaultVersion()
 * @method User|null getDefaultAssignee()
 * @method bool getInheritMembers()
 * @method bool isPublic()
 * @method RepeatableIdCollection getTrackers()
 * @method RepeatableNameCollection getEnabledModules()
 * @method IdentityCollection getTimeEntries()
 * @method IdentityCollection getTimeEntryActivities()
 * @method IdentityCollection getIssueCategories()
 * @method IdentityCollection getMemberships()
 * @method IdentityCollection getVersions()
 * @method IdentityCollection getFiles()
 * @method IdentityCollection getNews()
 * @method CarbonImmutable getCreatedOn()
 * @method CarbonImmutable getUpdatedOn()
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
    protected ?Version $default_version;
    protected ?User $default_assignee;

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
    protected PaginatedCollection $news;
    protected ArrayCollection $wiki_pages;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;

    public static function getRepositoryClass(): ?string
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