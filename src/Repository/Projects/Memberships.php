<?php

declare(strict_types=1);

namespace Blackmine\Repository\Projects;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\User\Membership;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

class Memberships extends AbstractRepository
{

    public const API_ROOT = "memberships";

    public function getModelClass(): string
    {
        return Membership::class;
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function all(?string $endpoint = null): IdentityCollection
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
}
