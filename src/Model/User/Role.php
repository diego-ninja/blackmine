<?php

namespace Blackmine\Model\User;

use Blackmine\Model\NamedIdentity;
use Blackmine\Repository\Users\Users;

class Role extends NamedIdentity
{
    public const ENTITY_NAME = "role";

    protected bool $assignable;
    protected string $issues_visibility;
    protected string $time_entries_visibility;
    protected string $users_visibility;
    protected array $permissions;

    public static function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}