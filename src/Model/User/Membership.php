<?php

namespace Dentaku\Redmine\Model\User;

use Dentaku\Redmine\Collection\RepeatableIdCollection;
use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Model\Project\Project;
use Dentaku\Redmine\Repository\Users\Users;

class Membership extends Identity
{
    public const ENTITY_NAME = "membership";

    protected Project $project;
    protected ?User $user;
    protected ?Group $group;

    protected RepeatableIdCollection $roles;

    protected static array $payload_mutations = [
        "user" => "user_id",
        "group" => "group_id",
        "roles" => "role_ids"
    ];

    public function getRepositoryClass(): ?string
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

}