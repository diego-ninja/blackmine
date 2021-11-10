<?php

declare(strict_types=1);

namespace Blackmine\Repository\Issues;

use Blackmine\Exception\MethodNotImplementedException;
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
    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function all(?string $endpoint = null): ArrayCollection
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }
}
