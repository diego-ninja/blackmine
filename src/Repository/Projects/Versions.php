<?php

declare(strict_types=1);

namespace Blackmine\Repository\Projects;

use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\Version;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

class Versions extends AbstractRepository
{
    public const API_ROOT = "versions";

    public function getModelClass(): string
    {
        return Version::class;
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
    public function all(?string $endpoint = null): ArrayCollection
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }
}
