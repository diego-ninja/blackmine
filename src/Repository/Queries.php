<?php

namespace Blackmine\Repository;

use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Query;
use Doctrine\Common\Collections\ArrayCollection;

class Queries extends AbstractRepository
{
    public const API_ROOT = "queries";

    public function getModelClass(): string
    {
        return Query::class;
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function search(array $params = []): ArrayCollection
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function get(mixed $id): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function delete(AbstractModel $model): void
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }
}
