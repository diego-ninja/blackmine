<?php

namespace Blackmine\Factory;

use Blackmine\Client\Client;
use Blackmine\Model\Enumeration\DocumentCategory;
use Blackmine\Model\Enumeration\IssuePriority;
use Blackmine\Model\Enumeration\TimeEntryActivity;
use Blackmine\Model\Issue\Status;
use Blackmine\Model\ModelInterface;
use Blackmine\Repository\Enumerations;

class MetaFactory
{
    private Client $client;

    private array $factories = [];
    private array $factory_definitions = [
        Status::class => null,
        IssuePriority::class => Enumerations::ENUM_ISSUE_PRIORITIES,
        TimeEntryActivity::class => Enumerations::ENUM_TIME_ENTRY_ACTIVITIES,
        DocumentCategory::class => Enumerations::ENUM_TYPE_DOCUMENT_CATEGORIES
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function make(string $model_class, int $id): ?ModelInterface
    {
        if (!isset($this->factories[$model_class])) {
            $this->factories[$model_class] = $this->getFactoryFor($model_class, $this->factory_definitions[$model_class]);
        }

        return $this->factories[$model_class]::make($id);
    }

    private function getFactoryFor(string $model_class, ?string $custom_endpoint): FactoryInterface
    {
        $repository_class = $model_class::getRepositoryClass();
        $values = $this->client->getRepository($repository_class::API_ROOT)->all($custom_endpoint)->toArray();

        return new NamedIdentityFactory($values, $model_class);
    }

}