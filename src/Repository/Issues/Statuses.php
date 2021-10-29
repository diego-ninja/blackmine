<?php

namespace Blackmine\Repository\Issues;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Issue\Status;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Statuses extends AbstractRepository
{
    public const API_ROOT = "issue_statuses";

    protected function getModelClass(): string
    {
        return Status::class;
    }

    public function get(mixed $id): ?AbstractModel
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method "  . __FUNCTION__ . "not implemented for apì: " . self::API_ROOT);
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function delete(AbstractModel $model): void
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

}