<?php

declare(strict_types=1);

namespace Blackmine\Model;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
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

    /**
     * @throws ReflectionException
     */
    public function __call(string $method, array $args): mixed
    {
        $method_prefix = Inflect::extractPrefix($method);

        return match($method_prefix) {
            Inflect::GETTER_PREFIX => $this->getter($method),
            Inflect::SETTER_PREFIX => $this->setter($method, $args),
            Inflect::ISSER_PREFIX => $this->isser($method),
            Inflect::ADDER_PREFIX => $this->adder($method, $args),
            Inflect::REMOVER_PREFIX => $this->remover($method, $args),
            default => null,
        };
    }

    /**
     * @throws ReflectionException
     */
    protected function setter(string $method, array $args): self
    {
        $property = $this->getProperty($method);
        if (property_exists($this, $property) && !is_null($args[0])) {
            $this->$property = $this->normalizeValue($property, $this->getPropertyType($property), $args[0]);
        }

        return $this;
    }

    protected function getter(string $method): mixed
    {
        $property = $this->getProperty($method);
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return null;
    }

    protected function isser(string $method): bool
    {
        $property = $this->getProperty($method);
        if (property_exists($this, $property)) {
            return (bool) $this->$property;
        }

        $property = "is_" . $property;
        if (property_exists($this, $property)) {
            return (bool) $this->$property;
        }

        return false;
    }

    protected function adder(string $method, array $args): self
    {
        $property = Inflect::pluralize($this->getProperty($method));
        if (property_exists($this, $property) && $this->$property instanceof Collection) {
            $found = $this->$property->find($args[0]);
            if (!$found) {
                $this->$property->add($args[0]);
            }
        }

        return $this;
    }

    protected function remover(string $method, array $args): self
    {
        $property = Inflect::pluralize($this->getProperty($method));
        if (property_exists($this, $property) && $this->$property instanceof Collection) {
            $found = $this->$property->find($args[0]);
            if ($found) {
                $key = $this->$property->indexOf($found);
                $this->$property->remove($key);
            }
        }

        return $this;
    }

    protected function getSetter(string $property): string
    {
        return Inflect::SETTER_PREFIX . Inflect::camelize($property);
    }

    protected function getGetter(string $property): string
    {
        return Inflect::GETTER_PREFIX . Inflect::camelize($property);
    }

    protected function getIsser(string $property): string
    {
        return Inflect::ISSER_PREFIX . Inflect::camelize($property);
    }

    protected function getAdder(string $property): string
    {
        return Inflect::ADDER_PREFIX . Inflect::camelize($property);
    }

    protected function getRemover(string $property): string
    {
        return Inflect::REMOVER_PREFIX . Inflect::camelize($property);
    }

    protected function getProperty(string $method): string
    {
        $test = preg_match('/[A-Z]/', $method, $matches, PREG_OFFSET_CAPTURE);
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
        return $p->getType()?->getName();
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

            if ($raw_value instanceof DateTimeImmutable) {
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

    protected function populateRelation(string $property, string $type, array $raw_value): ?ArrayCollection
    {
        $repository_class = static::getRepositoryClass();
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

        return new $type($raw_value);
    }

    protected function isRelatedModel(string $type): bool
    {
        if (class_exists($type)) {
            $interfaces = class_implements($type);
            return is_array($interfaces) && in_array(ModelInterface::class, $interfaces, true);
        }

        return false;
    }

    protected function isCollection(string $type): bool
    {
        if (class_exists($type)) {
            $interfaces = class_implements($type);
            return is_array($interfaces) && in_array(Collection::class, $interfaces, true);
        }

        return false;
    }
}
