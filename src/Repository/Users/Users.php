<?php

namespace Dentaku\Redmine\Repository\Users;

use Dentaku\Redmine\Model\User\Group;
use Dentaku\Redmine\Model\User\Membership;
use Dentaku\Redmine\Model\User\Role;
use Dentaku\Redmine\Repository\AbstractRepository;
use Dentaku\Redmine\Model\User\User;
use Dentaku\Redmine\Repository\RepositoryInterface;

class Users extends AbstractRepository
{
    public const API_ENDPOINT = "users";

    public const USER_RELATION_MEMBERSHIPS = "memberships";
    public const USER_RELATION_GROUPS = "groups";
    public const USER_RELATION_ROLES = "roles";

    public const USER_FILTER_NAME = "name";
    public const USER_FILTER_GROUP_ID = "group";

    protected static array $relation_class_map = [
        self::USER_RELATION_MEMBERSHIPS => Membership::class,
        self::USER_RELATION_GROUPS => Group::class,
        self::USER_RELATION_ROLES => Role::class
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