<?php

namespace Blackmine\Model\User;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Collection\RepeatableIdCollection;
use Blackmine\Model\NamedIdentity;
use Blackmine\Repository\Users\Groups;

class Group extends NamedIdentity
{
    public const ENTITY_NAME = "group";

    protected RepeatableIdCollection $users;
    protected IdentityCollection $memberships;

    public function __construct(protected ?int $id = null, protected  ?string $name = null)
    {
        $this->users = new RepeatableIdCollection();
        $this->memberships = new IdentityCollection();
    }

    protected static array $payload_mutations = [
        "users" => "user_ids"
    ];

    public function getRepositoryClass(): ?string
    {
        return Groups::class;
    }

}