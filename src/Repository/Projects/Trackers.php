<?php

namespace Blackmine\Repository\Projects;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\Tracker;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Trackers extends AbstractRepository
{
    public const API_ROOT = "trackers";

    protected function getModelClass(): string
    {
        return Tracker::class;
    }


    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function delete(AbstractModel $model): void
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

}