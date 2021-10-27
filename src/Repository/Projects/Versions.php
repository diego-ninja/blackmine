<?php

namespace Dentaku\Redmine\Repository\Projects;

use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Model\Project\Version;
use Dentaku\Redmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Versions extends AbstractRepository
{
    public const API_ROOT = "versions";

    protected function getModelClass(): string
    {
        return Version::class;
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function all(): ArrayCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

}