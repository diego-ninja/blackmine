<?php

declare(strict_types=1);

namespace Blackmine\Repository\Issues;

use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Issue\Status;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

class Statuses extends AbstractRepository
{
    public const API_ROOT = "issue_statuses";

    public function getModelClass(): string
    {
        return Status::class;
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function get(mixed $id): ?AbstractModel
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
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
