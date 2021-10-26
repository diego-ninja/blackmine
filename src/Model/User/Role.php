<?php

namespace Dentaku\Redmine\Model\User;

use Dentaku\Redmine\Model\NamedIdentity;
use Dentaku\Redmine\Repository\Users\Users;

class Role extends NamedIdentity
{
    public function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}