<?php

namespace Dentaku\Redmine\Model\User;

use Dentaku\Redmine\Model\NamedIdentity;
use Dentaku\Redmine\Repository\Users\Users;

class Role extends NamedIdentity
{
    public const ENTITY_NAME = "role";

    protected bool $assignable;
    protected string $issues_visibility;
    protected string $time_entries_visibility;
    protected string $users_visibility;
    protected array $permissions;

    public function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}