<?php

declare(strict_types=1);

namespace Blackmine\Repository\Users;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\User\Group;
use Blackmine\Model\User\Membership;
use Blackmine\Model\User\User;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\Collection;

/**
 * @method Group addUser(Group $group, User $user)
 * @method Group removeUser(Group $group, User $user)
 * @method IdentityCollection getMemberships()
 */
class Groups extends AbstractRepository
{

    public const API_ROOT = "groups";

    public const GROUP_RELATION_MEMBERSHIPS = "memberships";
    public const GROUP_RELATION_USERS = "users";

    protected static array $relation_class_map = [
        self::GROUP_RELATION_MEMBERSHIPS => Membership::class,
        self::GROUP_RELATION_USERS => User::class
    ];

    public function getModelClass(): string
    {
        return Group::class;
    }

    public function getUsers(Group $group): Collection
    {
        return $this->client->getRepository(Users::API_ROOT)
                ->addFilter(Users::USER_FILTER_GROUP_ID, $group->getId())
                ->search();
    }
}
