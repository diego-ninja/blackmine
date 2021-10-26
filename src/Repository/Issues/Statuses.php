<?php

namespace Dentaku\Redmine\Repository\Issues;

use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Model\Issue\Status;
use Dentaku\Redmine\Repository\AbstractRepository;
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
        throw new Error("Method not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method not implemented for apì: " . self::API_ROOT);
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method not implemented for apì: " . self::API_ROOT);
    }

    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method not implemented for apì: " . self::API_ROOT);
    }

}