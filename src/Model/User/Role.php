<?php

namespace Ninja\Redmine\Model\User;

use Ninja\Redmine\Model\Identity;
use Ninja\Redmine\Repository\Users\Users;

class Role extends Identity
{
    public function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}