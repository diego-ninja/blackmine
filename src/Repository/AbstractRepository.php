<?php

namespace Ninja\Redmine\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Error;
use JsonException;
use Ninja\Redmine\Client\Client;
use Ninja\Redmine\Model\AbstractModel;

abstract class AbstractRepository implements RepositoryInterface
{
    public const SEARCH_PARAM_LIMIT = "limit";
    public const SEARCH_PARAM_OFFSET = "offset";

    public const DEFAULT_LIMIT = 25;
    public const DEFAULT_OFFSET = 0;

    protected array $fetch_relations = [];
    protected static array $allowed_filters = [];

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
    public function get(int $id): ?AbstractModel
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

    /**
     * @param string|string[] $include
     * @return $this
     */
    public function include(string | array $include): self
    {
        if (!is_array($include)) {
            $include = [$include];
        }

        foreach ($include as $item) {
            if (array_key_exists($item, static::$relation_class_map)) {
                $this->fetch_relations[] = $item;
            }
        }

        return $this;
    }

    public static function getRelationClassFor(string $relation): ?string
    {
        return static::$relation_class_map[$relation] ?? null;
    }

    /**
     * @throws JsonException
     */
    public function search(array $params = []): ArrayCollection
    {
        $ret = new ArrayCollection();

        $search_endpoint = $this->getEndpoint() . "." . $this->client->getFormat();

        if (!empty($this->fetch_relations)) {
            $params["include"] = implode(",", $this->fetch_relations);
        }

        $defaults = [
            self::SEARCH_PARAM_LIMIT => self::DEFAULT_LIMIT,
            self::SEARCH_PARAM_OFFSET => self::DEFAULT_OFFSET
        ];

        if (!empty($this->includes)) {
            $params["include"] = implode(",", $this->includes);
        }

        $params = $this->sanitizeParams($defaults, $params);

        $limit = $params[self::SEARCH_PARAM_LIMIT];
        $offset = $params[self::SEARCH_PARAM_OFFSET];

        while ($limit > 0) {
            if ($limit > 100) {
                $_limit = 100;
                $limit -= 100;
            } else {
                $_limit = $limit;
                $limit = 0;
            }

            $params[self::SEARCH_PARAM_LIMIT] = $_limit;
            $params[self::SEARCH_PARAM_OFFSET] = $offset;

            $api_response = $this->client->get($this->constructEndpointUrl($search_endpoint, $params));

            if ($api_response->isSuccess()) {
                $ret = $this->populateCollection($api_response->getData()[$this->getEndpoint()], $ret);
                $offset += $_limit;
            }
        }

        return $ret;
    }

    protected function getEndpoint(): string
    {
        if (defined('static::API_ENDPOINT')) {
            return static::API_ENDPOINT;
        }

        throw new Error('Mandatory constant API_ENDPOINT not defined in class: ' . get_class($this));
    }

    protected function populateCollection(array $items, ArrayCollection $collection): ArrayCollection
    {
        foreach ($items as $item) {
            $object_class = $this->getModelClass();
            $object = new $object_class();
            $object->fromArray($item);

            $collection->add($object);
        }

        return $collection;
    }

    protected function isValidParameter(mixed $parameter, string $parameter_name): bool
    {
        $is_valid =
            false !== $parameter &&
            null !== $parameter &&
            '' !== $parameter &&
            !((is_array($parameter) || is_object($parameter)) && empty($parameter));

        if (!empty(self::$allowed_filters)) {
            return $is_valid && in_array($parameter_name, self::$allowed_filters, true);
        }

        return $is_valid;
    }

    protected function sanitizeParams(array $defaults, array $params): array
    {
        return array_filter(
            array_merge($defaults, $params),
            [$this, 'isValidParameter'],
            ARRAY_FILTER_USE_BOTH
        );
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