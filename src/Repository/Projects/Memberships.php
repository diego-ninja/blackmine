<?php

namespace Dentaku\Redmine\Repository\Projects;

use Dentaku\Redmine\Model\User\Membership;
use Dentaku\Redmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Memberships extends AbstractRepository
{

    public const API_ROOT = "memberships";

    protected function getModelClass(): string
    {
        return Membership::class;
    }

    public function all(): ArrayCollection
    {
        throw new Error("Method not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method not implemented for apì: " . self::API_ROOT);
    }

}