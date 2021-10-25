<?php

namespace Dentaku\Redmine\Model\User;

use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Repository\Users\Users;

class User extends AbstractModel
{
    public const ENTITY_NAME = "user";

    protected int $id;
    protected string $login;
    protected string $firstname;
    protected string $lastname;
    protected string $mail;
    protected int $status;
    protected string $api_key;

    protected array $custom_fields;
    protected ArrayCollection $memberships;
    protected ArrayCollection $groups;


    protected CarbonImmutable $created_on;
    protected CarbonImmutable $last_login_on;

    public function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}