<?php

namespace Blackmine\Repository\Users;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\User\Role;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Roles extends AbstractRepository
{
    public const API_ROOT = "roles";

    protected function getModelClass(): string
    {
        return Role::class;
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function delete(AbstractModel $model): void
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

}