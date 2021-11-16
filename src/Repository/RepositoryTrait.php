<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Client\ClientInterface;
use Blackmine\Client\Response\ApiResponse;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Collection\PaginatedCollection;
use Blackmine\Exception\Api\AbstractApiException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Identity;
use Blackmine\Tool\Inflect;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonException;

use function is_initialized;

trait RepositoryTrait
{

    protected array $fetch_relations = [];

    /**
     * Adds a relation to get to the query. It applies to the single get operations too.
     *
     * @param string|array $include
     * @return AbstractRepository|CacheableRepository|Issues\Issues|RepositoryTrait
     */
    public function with(string | array $include): self
    {
        if (!is_array($include)) {
            $include = [$include];
        }

        foreach ($include as $item) {
            $this->addRelationToFetch($item);
        }

        return $this;
    }

    protected function addRelations(array $params): array
    {
        if (!empty($this->fetch_relations)) {
            $params["include"] = implode(",", $this->fetch_relations);
        }

        return $params;
    }

    protected function hydrateRelations(AbstractModel $model): AbstractModel
    {
        foreach ($this->getFetchRelations() as $relation) {
            if ($this->isFetchable($relation)) {
                $getter = "get" . ucfirst(Inflect::camelize($relation));
                $setter = "set" . ucfirst(Inflect::camelize($relation));
                if (method_exists($this, $getter) || method_exists($this, "__call")) {
                    $collection = $this->$getter($model);
                    if ($collection) {
                        $model->$setter($collection);
                    }
                }
            }
        }

        return $model;
    }

    protected function updateRelations(AbstractModel $model): AbstractModel
    {
        foreach ($this->getRelationClassMap() as $relation_name => $relation_class) {
            $model_getter = $this->getGetter($relation_name);
            $repository_adder = $this->getAdder(Inflect::singularize($relation_name));

            if (is_initialized($model, $relation_name)) {
                $related_collection = $model->$model_getter();
                if ($related_collection instanceof Collection) {
                    foreach ($related_collection as $related_model) {
                        if (!$related_model->isPersisted()) {
                            $this->$repository_adder($model, $related_model);
                        }
                    }
                }
            }
        }

        return $model;
    }

    protected function isFetchable(string $relation_name): bool
    {
        $related_class = self::getRelationClassFor($relation_name);
        if ($related_class !== null && class_exists($related_class)) {
            $interfaces = class_implements($related_class);
            return is_array($interfaces) && in_array(FetchableInterface::class, $interfaces, true);
        }

        return false;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws AbstractApiException
     * @throws JsonException
     * @ignore
     */
    public function __call(string $method, array $args): mixed
    {
        if ($this->isRelationGetter($method)) {
            return $this->getRelation($method, $args);
        }

        if ($this->isRelationAdder($method)) {
            return $this->addRelation($method, $args);
        }

        return null;
    }

    protected function getRelation(string $method, array $args): ?Collection
    {
        $relation_name = strtolower(Inflect::snakeize(substr($method, 3)));
        $relation_class = $this->getRelationClassMap()[$relation_name];

        if ($args[0] instanceof AbstractModel) {
            $endpoint = $this->getEndpoint() . "/" . $args[0]->getId() . "/" . $relation_name . "." . $this->getClient()->getFormat();
            $response = $this->getClient()->get($endpoint);

            if ($response->isSuccess()) {
                $collection = $this->initCollectionFromResponse($response);

                foreach ($response->getData()[$relation_name] as $relation_data) {
                    $relation = (new $relation_class())->fromArray($relation_data);
                    $collection->add($relation);
                }

                return $collection;
            }
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    protected function addRelation(string $method, array $args): ?AbstractModel
    {
        $relation = Inflect::pluralize(strtolower(Inflect::snakeize(substr($method, 3))));
        if ($args[0] instanceof AbstractModel && $args[1] instanceof AbstractModel) {
            $endpoint = $this->getEndpoint() . "/" . $args[0]->getId() . "/" . $relation . "." . $this->getClient()->getFormat();
            $response = $this->getClient()->post($endpoint, json_encode($args[1]->getPayload(), JSON_THROW_ON_ERROR));

            if ($response->isSuccess()) {
                $adder = Inflect::ADDER_PREFIX . Inflect::singularize(Inflect::camelize($relation));
                $args[0]->$adder($args[1]);
            }
            return $args[0];
        }

        return null;
    }


    protected function isRelationGetter(string $method): bool
    {
        $relation = strtolower(Inflect::snakeize(substr($method, 3)));
        return str_starts_with($method, "get") && array_key_exists($relation, $this->getRelationClassMap());
    }

    protected function isRelationAdder(string $method): bool
    {
        $relation = strtolower(Inflect::pluralize(Inflect::snakeize(substr($method, 3))));
        return str_starts_with($method, "add") && array_key_exists($relation, $this->getRelationClassMap());
    }

    protected function getAdder(string $property): string
    {
        return "add" . Inflect::camelize($property);
    }

    protected function getGetter(string $property): string
    {
        return "get" . Inflect::camelize($property);
    }

    protected function initCollectionFromResponse(ApiResponse $response): Collection
    {
        if ($response->isPaginated()) {
            $collection = new PaginatedCollection();
            $collection->setLimit($response->getLimit());
            $collection->setOffset($response->getOffset());
            $collection->setTotalCount($response->getTotalCount());
        } else {
            $collection = new IdentityCollection();
        }

        return $collection;
    }

    protected function getCollection(array $items): ArrayCollection
    {
        $elements = [];

        foreach ($items as $item) {
            $object_class = $this->getModelClass();
            $object = new $object_class();
            $object->fromArray($item);

            $this->hydrateRelations($object);

            $elements[] = $object;
        }

        if (!empty($elements) && $elements[0] instanceof Identity) {
            return new IdentityCollection($elements);
        }

        return new ArrayCollection($elements);
    }


    /**
     * @return ClientInterface
     * @ignore
     */
    abstract public function getClient(): ClientInterface;

    /**
     * @param string $relation
     * @return string|null
     * @ignore
     */
    abstract public static function getRelationClassFor(string $relation): ?string;

    /**
     * @return array
     * @ignore
     */
    abstract public function getFetchRelations(): array;

    /**
     * @return array
     * @ignore
     */
    abstract public function getRelationClassMap(): array;

    /**
     * @return string
     * @ignore
     */
    abstract public function getEndpoint(): string;
}
