<?php

declare(strict_types=1);

namespace Blackmine\Repository\Users;

use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\User\Role;
use Blackmine\Repository\AbstractRepository;

class Roles extends AbstractRepository
{
    public const API_ROOT = "roles";

    public function getModelClass(): string
    {
        return Role::class;
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function delete(AbstractModel $model): void
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }
}
