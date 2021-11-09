<?php

declare(strict_types=1);

namespace Blackmine\Model\User;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\NamedIdentity;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Blackmine\Repository\Users\Groups;

/**
 * @method void setUsers(RepeatableIdCollection $users)
 * @method void setMemberships(IdentityCollection $memberships)
 *
 * @method void addUser(User $user)
 * @method void removeUser(User $user)
 * @method void addMembership(Membership $membership)
 * @method void removeMembership(Membership $membership)
 *
 * @method RepeatableIdCollection getUsers()
 * @method IdentityCollection getMemberships()
 */
class Group extends NamedIdentity implements MutableInterface
{
    public const ENTITY_NAME = "group";

    protected RepeatableIdCollection $users;
    protected IdentityCollection $memberships;

    public function __construct(protected ?int $id = null, protected ?string $name = null)
    {
        $this->users = new RepeatableIdCollection();
        $this->memberships = new IdentityCollection();
    }

    public static function getRepositoryClass(): ?string
    {
        return Groups::class;
    }

    public function getMutations(): array
    {
        return [
            "users" => [RenameKeyMutation::class => ["user_ids"]]
        ];
    }
}
