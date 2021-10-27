<?php

namespace Dentaku\Redmine\Repository;

use Dentaku\Redmine\Collection\IdentityCollection;
use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Model\FetchableInterface;
use Dentaku\Redmine\Tool\Inflect;
use Doctrine\Common\Collections\Collection;
use JsonException;
use ReflectionException;
use ReflectionProperty;

trait RepositoryTrait
{
    private function hydrateRelations(AbstractModel $model): AbstractModel
    {
        foreach ($this->fetch_relations as $relation) {
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

    private function updateRelations(AbstractModel $model): AbstractModel
    {
        foreach (static::$relation_class_map as $relation_name => $relation_class) {
            $model_getter = $this->getGetter($relation_name);
            $repository_adder = $this->getAdder(Inflect::singularize($relation_name));

            if ($this->isInitialized($model, $relation_name)) {
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

    /**
     * @throws ReflectionException
     */
    private function isInitialized(AbstractModel $model, string $property): bool
    {
        $rp = new ReflectionProperty(get_class($model), $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($model);
    }

    private function isFetchable(string $relation_name): bool
    {
        $related_class = static::getRelationClassFor($relation_name);
        if (class_exists($related_class)) {
            $interfaces = class_implements($related_class);
            return $interfaces && in_array(FetchableInterface::class, $interfaces, true);

        }

        return false;

    }

    /**
     * @throws JsonException
     */
    public function __call(string $method, array $args): mixed
    {
        if ($this->isRelationGetter($method)) {
            $relation_name = strtolower(Inflect::snakeize(substr($method, 3)));
            $relation_class = static::$relation_class_map[$relation_name];

            if ($args[0] instanceof AbstractModel) {
                $endpoint = $this->getEndpoint() . "/" . $args[0]->getId() . "/" . $relation_name . "." . $this->client->getFormat();
                $response = $this->client->get($endpoint);

                if ($response->isSuccess()) {
                    $ret = new IdentityCollection();
                    foreach ($response->getData()[$relation_name] as $relation_data) {
                        $relation = (new $relation_class())->fromArray($relation_data);
                        $ret->add($relation);
                    }

                    return $ret;

                }
            }

            return null;
        }

        if ($this->isRelationAdder($method)) {
            $relation = Inflect::pluralize(strtolower(Inflect::snakeize(substr($method, 3))));
            if ($args[0] instanceof AbstractModel && $args[1] instanceof AbstractModel) {
                $endpoint = $this->getEndpoint() . "/" . $args[0]->getId() . "/" . $relation .  "." . $this->client->getFormat();
                $response = $this->client->post($endpoint, json_encode($args[1]->getPayload(), JSON_THROW_ON_ERROR));

                if ($response->isSuccess()) {
                    $adder = "add" . Inflect::singularize(Inflect::camelize($relation));
                    $args[0]->$adder($args[1]);
                }
                return $args[0];
            }
        }

        return null;
    }


    protected function isRelationGetter(string $method): bool
    {
        $relation = strtolower(Inflect::snakeize(substr($method, 3)));
        return str_starts_with($method, "get") && array_key_exists($relation, static::$relation_class_map);
    }

    protected function isRelationAdder(string $method): bool
    {
        $relation = strtolower(Inflect::pluralize(Inflect::snakeize(substr($method, 3))));
        return str_starts_with($method, "add") && array_key_exists($relation, static::$relation_class_map);
    }

    protected function getAdder(string $property): string
    {
        return "add" . Inflect::camelize($property);
    }

    protected function getGetter(string $property): string
    {
        return "get" . Inflect::camelize($property);
    }

}