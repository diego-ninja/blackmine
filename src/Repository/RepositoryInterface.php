<?php

namespace Ninja\Redmine\Repository;

use Ninja\Redmine\Model\AbstractModel;

interface RepositoryInterface
{
    public function get(int $id): ?AbstractModel;
    public function create(AbstractModel $model): ?AbstractModel;
    public function update(AbstractModel $model): ?AbstractModel;
    public function delete(AbstractModel $model): void;

}