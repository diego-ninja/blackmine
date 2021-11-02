<?php

namespace Blackmine\Model\User;

use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\Identity;
use Blackmine\Model\Project\Project;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Blackmine\Repository\Users\Users;

class Membership extends Identity implements MutableInterface
{
    public const ENTITY_NAME = "membership";

    protected Project $project;
    protected ?User $user;
    protected ?Group $group;

    protected RepeatableIdCollection $roles;

    public static function getRepositoryClass(): ?string
    {
        return Users::class;
    }

    public function addRole(Role $role): void
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    public function removeRole(Role $role): void
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
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