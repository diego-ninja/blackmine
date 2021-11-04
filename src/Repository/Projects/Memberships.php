<?php

declare(strict_types=1);

namespace Blackmine\Repository\Projects;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\User\Membership;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Memberships extends AbstractRepository
{

    public const API_ROOT = "memberships";

    protected function getModelClass(): string
    {
        return Membership::class;
    }

    public function all(?string $endpoint = null): IdentityCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

}