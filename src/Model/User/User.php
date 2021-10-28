<?php

namespace Blackmine\Model\User;

use Carbon\CarbonImmutable;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\Identity;
use Doctrine\Common\Collections\ArrayCollection;
use Blackmine\Model\AbstractModel;
use Blackmine\Repository\Users\Users;

class User extends Identity
{
    public const ENTITY_NAME = "user";

    protected string $login;
    protected string $firstname;
    protected string $lastname;
    protected string $mail;
    protected int $status;
    protected string $api_key;

    protected IdentityCollection $custom_fields;
    protected IdentityCollection $memberships;
    protected IdentityCollection $groups;


    protected CarbonImmutable $created_on;
    protected CarbonImmutable $last_login_on;

    public function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}