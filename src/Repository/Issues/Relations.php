<?php

declare(strict_types=1);

namespace Blackmine\Repository\Issues;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Issue\Relation;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Relations extends AbstractRepository
{
    public const API_ROOT = "relations";

    public function getModelClass(): string
    {
        return Relation::class;
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method "  . __FUNCTION__ . "not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method "  . __FUNCTION__ . "not implemented for apì: " . self::API_ROOT);
    }

    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function all(?string $endpoint = null): ArrayCollection
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }
}
