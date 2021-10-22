<?php

namespace Ninja\Redmine\Model\User;

use Doctrine\Common\Collections\ArrayCollection;
use Ninja\Redmine\Model\AbstractModel;
use Ninja\Redmine\Model\Identity;
use Ninja\Redmine\Repository\Users\Users;

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