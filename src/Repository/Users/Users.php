<?php

namespace Ninja\Redmine\Repository\Users;

use Ninja\Redmine\Model\User\Group;
use Ninja\Redmine\Model\User\Membership;
use Ninja\Redmine\Model\User\Role;
use Ninja\Redmine\Repository\AbstractRepository;
use Ninja\Redmine\Model\User\User;

class Users extends AbstractRepository
{
    public const API_ENDPOINT = "users";

    public const USER_RELATION_MEMBERSHIPS = "memberships";
    public const USER_RELATION_GROUPS = "groups";
    public const USER_RELATION_ROLES = "roles";

    protected static array $relation_class_map = [
        self::USER_RELATION_MEMBERSHIPS => Membership::class,
        self::USER_RELATION_GROUPS => Group::class,
        self::USER_RELATION_ROLES => Role::class
    ];

    protected static array $allowed_filters = [];

    public function getModelClass(): string
    {
        return User::class;
    }

}