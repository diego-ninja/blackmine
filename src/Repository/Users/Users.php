<?php

namespace Blackmine\Repository\Users;

use Blackmine\Model\CustomField;
use Blackmine\Model\User\Group;
use Blackmine\Model\User\Membership;
use Blackmine\Model\User\Role;
use Blackmine\Repository\AbstractRepository;
use Blackmine\Model\User\User;
use Blackmine\Repository\RepositoryInterface;

class Users extends AbstractRepository
{
    public const API_ROOT = "users";

    public const USER_RELATION_MEMBERSHIPS = "memberships";
    public const USER_RELATION_GROUPS = "groups";
    public const USER_RELATION_ROLES = "roles";
    public const USER_RELATION_CUSTOM_FIELDS = "custom_fields";

    public const USER_FILTER_NAME = "name";
    public const USER_FILTER_GROUP_ID = "group_id";

    protected static array $relation_class_map = [
        self::USER_RELATION_MEMBERSHIPS => Membership::class,
        self::USER_RELATION_GROUPS => Group::class,
        self::USER_RELATION_ROLES => Role::class,
        self::USER_RELATION_CUSTOM_FIELDS => CustomField::class
    ];

    protected static array $allowed_filters = [
        self::USER_FILTER_NAME => RepositoryInterface::SEARCH_PARAM_TYPE_STRING,
        self::USER_FILTER_GROUP_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT
    ];

    public function getModelClass(): string
    {
        return User::class;
    }

}