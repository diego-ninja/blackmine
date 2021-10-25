<?php

namespace Ninja\Redmine\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Error;
use JsonException;
use Ninja\Redmine\Client\Client;
use Ninja\Redmine\Model\AbstractModel;

abstract class AbstractRepository implements RepositoryInterface
{
    use SearchableTrait;

    protected array $fetch_relations = [];

    public function __construct(
        protected Client $client,
        protected array $options = []
    ) {
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
            $this->getEndpoint() . "." . $this->client->getFormat(),
            json_encode($model->getPayload(), JSON_THROW_ON_ERROR)
        );

        if ($api_response->isSuccess()) {
            $model = new $model_class();
            $model->fromArray($api_response->getData()[$model->getName()]);

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

        $api_response = $this->client->get($this->constructEndpointUrl($endpoint_url, $params));

        if ($api_response->isSuccess()) {
            $model_class = $this->getModelClass();
            $model = new $model_class();
            $model->fromArray($api_response->getData()[$model->getEntityName()]);

            return $model;

        }

        return null;

    }

    /**
     * @throws JsonException
     */
    public function all(): ArrayCollection
    {
        $ret = new ArrayCollection();

        $api_response = $this->client->get($this->getEndpoint() . "." . $this->client->getFormat());
        if (isset($api_response->getData()[$this->getEndpoint()])) {
            $ret = $this->populateCollection($api_response->getData()[$this->getEndpoint()], $ret);
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

        $api_response = $this->client->put(
            $this->getEndpoint() . "/" . $model->getId() . "." . $this->client->getFormat(),
            json_encode($model->getPayload(), JSON_THROW_ON_ERROR)
        );

        if ($api_response->isSuccess()) {
            return $this->get($model->getId());
        }

        return null;
    }

    public function delete(AbstractModel $model): void
    {
        $endpoint_url = $this->getEndpoint() . "/" . $model->getId() . "." . $this->client->getFormat();
        $api_response = $this->client->delete($endpoint_url);

    }

    public static function getRelationClassFor(string $relation): ?string
    {
        return static::$relation_class_map[$relation] ?? null;
    }

    protected function getEndpoint(): string
    {
        if (defined('static::API_ENDPOINT')) {
            return static::API_ENDPOINT;
        }

        throw new Error('Mandatory constant API_ENDPOINT not defined in class: ' . get_class($this));
    }

    protected function constructEndpointUrl(string $url, array $params): string
    {
        if (empty($params)) {
            return $url;
        }

        return $url . '?' . preg_replace(
                '/%5B[0-9]+%5D/simU',
                '%5B%5D',
                http_build_query($params));
    }


    abstract protected function getModelClass(): string;

}