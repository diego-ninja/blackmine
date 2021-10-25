<?php

namespace Dentaku\Redmine\Model\User;

use Doctrine\Common\Collections\ArrayCollection;
use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Model\Identity;
use Dentaku\Redmine\Repository\Users\Users;

class Membership extends AbstractModel
{
    protected int $id;
    protected Identity $project;
    protected ArrayCollection $roles;

    public function getRepositoryClass(): ?string
    {
        return Users::class;
    }

}