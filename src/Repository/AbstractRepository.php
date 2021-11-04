<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Client\ClientInterface;
use Blackmine\Client\ClientOptions;
use Blackmine\Model\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Error;
use Blackmine\Client\Client;
use Blackmine\Model\AbstractModel;
use JsonException;

abstract class AbstractRepository implements RepositoryInterface
{
    use RepositoryTrait;
    use SearchableTrait;

    protected array $fetch_relations = [];

    public function __construct(
        protected Client $client,
        protected array $options = [
            ClientOptions::CLIENT_OPTION_REQUEST_HEADERS => []
        ]
    ) {
        $this->reset();
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function actingAs(string | User $user): self
    {
        if ($user instanceof User) {
            $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS][ClientOptions::REDMINE_IMPERSONATE_HEADER] = $user->getLogin();
        } else {
            $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS][ClientOptions::REDMINE_IMPERSONATE_HEADER] = $user;
        }

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function create(AbstractModel $model): ?AbstractModel
    {
        $model_class = $this->getModelClass();
        if (!$model instanceof $model_class) {
            throw new Error('Wrong model class for ' . $this->getEndpoint() . " api. Expected " . $this->getModelClass());
        }

        $api_response = $this->client->post(
            endpoint: $this->getEndpoint() . "." . $this->client->getFormat(),
            body: json_encode($model->getPayload(), JSON_THROW_ON_ERROR),
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

        if ($api_response->isSuccess()) {
            $model->fromArray($api_response->getData()[$model->getEntityName()]);

            $this->updateRelations($model);

            return $model;
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public function get(mixed $id): ?AbstractModel
    {
        $params = [];
        $endpoint_url = $this->getEndpoint() . "/" . $id . "." . $this->client->getFormat();

        if (!empty($this->fetch_relations)) {
            $params["include"] = implode(",", $this->fetch_relations);
        }

        $api_response = $this->client->get(
            endpoint: $this->constructEndpointUrl($endpoint_url, $params),
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

        if ($api_response->isSuccess()) {
            $model_class = $this->getModelClass();
            $model = new $model_class();
            $model->fromArray($api_response->getData()[$model->getEntityName()]);

            $this->hydrateRelations($model);

            return $model;

        }

        return null;

    }

    /**
     * @throws JsonException
     */
    public function all(?string $endpoint = null): ArrayCollection
    {
        $ret = new ArrayCollection();

        $api_endpoint = $endpoint ?? $this->getEndpoint();

        $api_response = $this->client->get(
            endpoint: $api_endpoint . "." . $this->client->getFormat(),
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );
        if (isset($api_response->getData()[static::API_ROOT])) {
            $ret = $this->getCollection($api_response->getData()[static::API_ROOT]);
        }

        return $ret;

    }

    /**
     * @throws JsonException
     */
    public function update(AbstractModel $model): ?AbstractModel
    {
        $model_class = $this->getModelClass();
        if (!$model instanceof $model_class) {
            throw new Error('Wrong model class for ' . $this->getEndpoint() . " api. Expected " . $this->getModelClass());
        }

        $this->updateRelations($model);

        $api_response = $this->client->put(
            endpoint: $this->getEndpoint() . "/" . $model->getId() . "." . $this->client->getFormat(),
            body: json_encode($model->getPayload(), JSON_THROW_ON_ERROR),
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

        if ($api_response->isSuccess()) {
            return $model;
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public function delete(AbstractModel $model): void
    {
        $endpoint_url = $this->getEndpoint() . "/" . $model->getId() . "." . $this->client->getFormat();
        $api_response = $this->client->delete(
            endpoint: $endpoint_url,
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

    }

    /**
     * @throws JsonException
     */
    public function search(): ArrayCollection
    {
        return $this->doSearch();
    }

    public static function getRelationClassFor(string $relation): ?string
    {
        return static::$relation_class_map[$relation] ?? null;
    }

    public function getEndpoint(): string
    {
        if (defined('static::API_ROOT')) {
            return static::API_ROOT;
        }

        throw new Error('Mandatory constant API_ROOT not defined in class: ' . get_class($this));
    }

    public function getAllowedFilters(): array
    {
        return static::$allowed_filters;
    }

    public function getRelationClassMap(): array
    {
        return static::$relation_class_map;
    }

    public function getFetchRelations(): array
    {
        return $this->fetch_relations;
    }

    public function constructEndpointUrl(string $url, array $params): string
    {
        if (empty($params)) {
            return $url;
        }

        return $url . '?' . preg_replace(
                '/%5B[0-9]+%5D/simU',
                '%5B%5D',
                http_build_query($params));
    }

    abstract public function getModelClass(): string;

}