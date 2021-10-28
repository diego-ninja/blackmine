<?php

namespace Blackmine\Model;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Blackmine\Tool\Inflect;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ReflectionException;
use ReflectionProperty;

trait ModelTrait
{
    protected static array $direct_types = [
        "int", "string", "float", "array", "bool", "mixed"
    ];

    public function __call(string $method, array $args): mixed
    {
        if ($this->isSetter($method)) {
            $property = $this->getProperty($method);
            if (property_exists($this, $property) && !is_null($args[0])) {
                try {
                    $this->$property = $this->normalizeValue($property, $this->getPropertyType($property), $args[0]);
                } catch (ReflectionException $e) {

                }
            }
        }

        if ($this->isGetter($method)) {
            $property = $this->getProperty($method);
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        }

        if ($this->isAdder($method)) {
            $property = Inflect::pluralize($this->getProperty($method));
            if (property_exists($this, $property) && $this->$property instanceof Collection) {
                $found = $this->$property->find($args[0]);
                if (!$found) {
                    $this->$property->add($args[0]);
                }
            }
        }

        if ($this->isRemover($method)) {
            $property = Inflect::pluralize($this->getProperty($method));
            if (property_exists($this, $property) && $this->$property instanceof Collection) {
                $found = $this->$property->find($args[0]);
                if ($found) {
                    $key = $this->$property->indexOf($found);
                    $this->$property->remove($key);
                }

            }

        }

        return null;
    }

    protected function isSetter(string $method): bool
    {
        return str_starts_with($method, "set");
    }

    protected function isGetter(string $method): bool
    {
        return str_starts_with($method, "get");
    }

    protected function isAdder(string $method): bool
    {
        return str_starts_with($method, "add");
    }

    protected function isRemover(string $method): bool
    {
        return str_starts_with($method, "remove");
    }

    protected function getSetter(string $property): string
    {
        return "set" . Inflect::camelize($property);
    }

    protected function getGetter(string $property): string
    {
        return "get" . Inflect::camelize($property);
    }

    protected function getAdder(string $property): string
    {
        return "add" . Inflect::camelize($property);
    }

    protected function getRemover(string $property): string
    {
        return "remove" . Inflect::camelize($property);
    }

    protected function getProperty(string $method): string
    {
        $test = preg_match( '/[A-Z]/', $method, $matches, PREG_OFFSET_CAPTURE );
        if ($test) {
            return Inflect::snakeize(substr($method, $matches[0][1]));
        }

        return $method;
    }

    /**
     * @throws ReflectionException
     */
    protected function getPropertyType(string $property): string
    {
        $p = new ReflectionProperty($this, $property);
        return $p->getType()->getName();
    }

    protected function normalizeValue(string $property, string $type, mixed $raw_value): mixed
    {
        if ($this->isRelatedModel($type)) {
            if ($raw_value instanceof $type) {
                return $raw_value;
            }

            $value = new $type();
            $value->fromArray($raw_value);

            return $value;
        }

        if (is_array($raw_value) && $this->isCollection($type)) {
            return $this->populateRelation($property, $type, $raw_value);

        }

        if ($raw_value instanceof Collection && $this->isCollection($type)) {
            if (get_class($raw_value) !== $type) {
                if (method_exists($raw_value, "getElements")) {
                    return new $type($raw_value->getElements());
                }

                return new $type($raw_value->toArray());
            }

            return $raw_value;
        }

        if ($type === CarbonImmutable::class) {
            if ($raw_value instanceof CarbonImmutable) {
                return $raw_value;
            }

            if($raw_value instanceof DateTimeImmutable) {
                return CarbonImmutable::createFromTimestamp($raw_value->getTimestamp());
            }

            $timestamp = strtotime($raw_value);
            return Carbon::createFromTimestamp($timestamp)->toImmutable();
        }

        if ($this->isDirectType($type)) {
            return $raw_value;
        }

        return null;
    }

    protected function isDirectType(string $type): bool
    {
        return in_array($type, self::$direct_types, true);
    }

    protected function populateRelation(string $property, string $type, array $raw_value): ?ArrayCollection {
        $repository_class = $this->getRepositoryClass();
        $model_class = $repository_class::getRelationClassFor($property);

        if ($model_class) {
            $ret = new $type();
            foreach ($raw_value as $item) {
                if ($item instanceof $model_class) {
                    $ret->add($item);
                }

                if (is_array($item)) {
                    $model = new $model_class();
                    $model->fromArray($item);
                    $ret->add($model);
                }

            }

            return $ret;
        }

        return null;
    }

    protected function isRelatedModel(string $type): bool
    {
        if (class_exists($type)) {
            $interfaces = class_implements($type);
            return $interfaces && in_array(ModelInterface::class, $interfaces, true);

        }

        return false;
    }

    protected function isCollection(string $type): bool
    {
        if (class_exists($type)) {
            $interfaces = class_implements($type);
            return $interfaces && in_array(Collection::class, $interfaces, true);

        }

        return false;
    }

}
