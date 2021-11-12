<?php

declare(strict_types=1);

namespace Blackmine\Repository\Projects;

use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\Tracker;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

class Trackers extends AbstractRepository
{
    public const API_ROOT = "trackers";

    public function getModelClass(): string
    {
        return Tracker::class;
    }


    /**
     * @throws MethodNotImplementedException
     */
    public function search(array $params = []): ArrayCollection
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
