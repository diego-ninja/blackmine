<?php

namespace Dentaku\Redmine\Model\User;

use Dentaku\Redmine\Collection\IdentityCollection;
use Dentaku\Redmine\Collection\RepeatableIdCollection;
use Dentaku\Redmine\Model\NamedIdentity;
use Dentaku\Redmine\Repository\Users\Groups;

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