<?php

declare(strict_types=1);

namespace Blackmine\Model\User;

use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\Identity;
use Blackmine\Model\Project\Project;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Blackmine\Repository\Projects\Memberships;

/**
 * @method void setProject(Project $project)
 * @method void setUser(User $user)
 * @method void setGroup(Group $group)
 * @method void setRoles(RepeatableIdCollection $roles)
 *
 * @method void addRole(Role $role)
 * @method void removeRole(Role $role)
 */
class Membership extends Identity implements MutableInterface
{
    public const ENTITY_NAME = "membership";

    protected Project $project;
    protected ?User $user;
    protected ?Group $group;

    protected RepeatableIdCollection $roles;

    public static function getRepositoryClass(): ?string
    {
        return Memberships::class;
    }

    public function getMutations(): array
    {
        return [
            "user" => [RenameKeyMutation::class => ["user_id"]],
            "group" => [RenameKeyMutation::class => ["group_id"]],
            "roles" => [RenameKeyMutation::class => ["role_ids"]]
        ];
    }
}
