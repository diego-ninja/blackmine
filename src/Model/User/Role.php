<?php

declare(strict_types=1);

namespace Blackmine\Model\User;

use Blackmine\Model\NamedIdentity;
use Blackmine\Repository\Users\Roles;
use Blackmine\Repository\Users\Users;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method void setAssignable(bool $assignable)
 * @method void setIssuesVisibility(string $issues_visibility)
 * @method void setTimeEntriesVisibility(string $time_entries_visibility)
 * @method void setUsersVisibility(string $users_visibility)
 * @method void setPermissions(array|ArrayCollection $permissions)
 *
 * @method bool isAssignable()
 * @method string getIssuesVisibility()
 * @method string getTimeEntriesVisibility()
 * @method string getUsersVisibility()
 * @method ArrayCollection getPermissions()
 */
class Role extends NamedIdentity
{
    public const ENTITY_NAME = "role";

    protected bool $assignable;
    protected string $issues_visibility;
    protected string $time_entries_visibility;
    protected string $users_visibility;
    protected ?ArrayCollection $permissions;

    public static function getRepositoryClass(): ?string
    {
        return Roles::class;
    }

}