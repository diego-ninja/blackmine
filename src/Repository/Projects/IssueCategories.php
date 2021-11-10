<?php

declare(strict_types=1);

namespace Blackmine\Repository\Projects;

use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\IssueCategory;
use Blackmine\Repository\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

class IssueCategories extends AbstractRepository
{
    public const API_ROOT = "issue_categories";

    public function getModelClass(): string
    {
        return IssueCategory::class;
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function all(?string $endpoint = null): ArrayCollection
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
    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function delete(AbstractModel $model): void
    {
        /** @var IssueCategory $model */
        if ($model->getReassignTo() !== null) {
            $endpoint_url = $this->getEndpoint() . "/" . $model->getId() . "." . $this->client->getFormat() . "?reassign_to_id=" . $model->getReassignTo()->getId();
            $this->client->delete($endpoint_url);
        } else {
            parent::delete($model);
        }
    }
}
