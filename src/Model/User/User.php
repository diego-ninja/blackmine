<?php

namespace Blackmine\Model\User;

use Blackmine\Model\CustomField;
use Blackmine\Model\NamedIdentity;
use Carbon\CarbonImmutable;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Repository\Users\Users;

/**
 * @method void setLogin(string $login)
 * @method void setFirstname(string $firstname)
 * @method void setLastname(string $lastname)
 * @method void setMail(string $mail)
 * @method void setStatus(int $status)
 *
 * @method string getLogin()
 * @method string getFirstname()
 * @method string getLastname()
 * @method string getMail()
 * @method string getStatus()
 * @method CarbonImmutable getCreatedOn()
 * @method CarbonImmutable getLastLoginOn()
 * @method IdentityCollection getCustomFields()
 * @method IdentityCollection getMemberships()
 * @method IdentityCollection getGroups()
 *
 * @method addCustomField(CustomField $custom_field)
 * @method removeCustomField(CustomField $custom_field)
 * @method addGroup(Group $group)
 * @method removeGroup(Group $group)
 * @method addMembership(Membership $membership)
 * @method removeMembership(Membership $membership)
 */
class User extends NamedIdentity
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

    public static function getRepositoryClass(): ?string
    {
        return Users::class;
    }

    public function getName(): string
    {
        return $this->firstname . " " . $this->lastname;
    }

}