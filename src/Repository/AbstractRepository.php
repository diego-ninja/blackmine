<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Client\ClientInterface;
use Blackmine\Client\ClientOptions;
use Blackmine\Client\Client;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Error;
use JsonException;
use Blackmine\Exception\Api\AbstractApiException;
use Blackmine\Exception\InvalidModelException;
use Blackmine\Exception\Api\EntityNotFoundException;

abstract class AbstractRepository implements RepositoryInterface
{
    use RepositoryTrait;
    use SearchableTrait;

    protected static array $relation_class_map = [];

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
            $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS][ClientOptions::REDMINE_IMPERSONATE_HEADER] =
                $user->getLogin();
        } else {
            $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS][ClientOptions::REDMINE_IMPERSONATE_HEADER] =
                $user;
        }

        return $this;
    }

    /**
     * @throws JsonException
     * @throws EntityNotFoundException
     * @throws AbstractApiException
     */
    public function get(mixed $id): ?AbstractModel
    {
        $params = [];
        $endpoint_url = $this->getEndpoint() . "/" . $id . "." . $this->client->getFormat();

        if (!empty($this->getFetchRelations())) {
            $params["include"] = implode(",", $this->getFetchRelations());
        }

        $api_response = $this->client->get(
            endpoint: $this->constructEndpointUrl($endpoint_url, $params),
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

        if ($api_response->isSuccess()) {
            $model_class = $this->getModelClass();
            $model = new $model_class();
            $model_data = $api_response->getData()[$model->getEntityName()] ?? null;

            if ($model_data) {
                $model->fromArray($model_data);
                $this->hydrateRelations($model);

                return $model;
            }

            throw new EntityNotFoundException();
        }

        throw AbstractApiException::fromApiResponse($api_response);
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
     * @throws InvalidModelException
     * @throws AbstractApiException
     */
    public function create(AbstractModel $model): ?AbstractModel
    {
        $model_class = $this->getModelClass();
        if (!$model instanceof $model_class) {
            throw new InvalidModelException(
                'Wrong model class for ' . $this->getEndpoint() . " api. Expected " . $this->getModelClass()
            );
        }

        $api_response = $this->client->post(
            endpoint: $this->getEndpoint() . "." . $this->client->getFormat(),
            body: json_encode($model->getPayload(), JSON_THROW_ON_ERROR),
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

        if ($api_response->isSuccess()) {
            $model_data = $api_response->getData()[$model->getEntityName()] ?? null;

            if ($model_data) {
                $model->fromArray($model_data);
                $this->updateRelations($model);

                return $model;
            }
        }

        throw AbstractApiException::fromApiResponse($api_response);
    }

    /**
     * @throws JsonException
     * @throws InvalidModelException
     * @throws AbstractApiException
     */
    public function update(AbstractModel $model): ?AbstractModel
    {
        $model_class = $this->getModelClass();
        if (!$model instanceof $model_class) {
            throw new InvalidModelException(
                'Wrong model class for ' . $this->getEndpoint() . " api. Expected " . $this->getModelClass()
            );
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

        throw AbstractApiException::fromApiResponse($api_response);
    }

    /**
     * @throws AbstractApiException
     * @throws InvalidModelException
     * @throws JsonException
     */
    public function delete(AbstractModel $model): void
    {
        $model_class = $this->getModelClass();
        if (!$model instanceof $model_class) {
            throw new InvalidModelException(
                'Wrong model class for ' . $this->getEndpoint() . " api. Expected " . $this->getModelClass()
            );
        }

        $endpoint_url = $this->getEndpoint() . "/" . $model->getId() . "." . $this->client->getFormat();
        $api_response = $this->client->delete(
            endpoint: $endpoint_url,
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

        if (!$api_response->isSuccess()) {
            throw AbstractApiException::fromApiResponse($api_response);
        }
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

    public function addRelationToFetch(string $relation): void
    {
        if (array_key_exists($relation, $this->getRelationClassMap())) {
            $this->fetch_relations[] = $relation;
        }
    }

    public function constructEndpointUrl(string $url, array $params): string
    {
        if (empty($params)) {
            return $url;
        }

        return $url . '?' . preg_replace(
            '/%5B[0-9]+%5D/simU',
            '%5B%5D',
            http_build_query($params)
        );
    }

    abstract public function getModelClass(): string;
}
