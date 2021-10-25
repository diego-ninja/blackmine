<?php

namespace Dentaku\Redmine\Model\User;

use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Repository\Users\Users;

class Group extends Identity
{
    public function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}