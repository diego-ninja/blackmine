<?php

namespace Blackmine\Repository\Projects;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\Version;
use Blackmine\Repository\AbstractRepository;
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

    public function all(?string $endpoint = null): ArrayCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

}