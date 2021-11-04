<?php

declare(strict_types=1);

namespace Blackmine\Model\User;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\NamedIdentity;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Blackmine\Repository\Users\Groups;

class Group extends NamedIdentity implements MutableInterface
{
    public const ENTITY_NAME = "group";

    protected RepeatableIdCollection $users;
    protected IdentityCollection $memberships;

    public function __construct(protected ?int $id = null, protected  ?string $name = null)
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