<?php

namespace Ninja\Redmine\Model;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
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
            if (property_exists($this, $this->getProperty($method))) {
                return $this->$property;
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

    protected function getSetter(string $property): string
    {
        return "set" . $this->camelize($property);
    }

    protected function getGetter(string $property): string
    {
        return "get" . $this->camelize($property);
    }

    protected function getProperty(string $method): string
    {
        return $this->snakeize(substr($method, 3));
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

        if ($type === CarbonImmutable::class) {
            $timestamp = strtotime($raw_value);
            return Carbon::createFromTimestamp($timestamp)->toImmutable();
        }

        if ($this->isDirectType($type)) {
            return $raw_value;
        }

        return null;
    }

    protected function snakeize(string $input): string
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('_', $ret);
    }

    protected function camelize(string $input, string $delimiter = "_"): string
    {
        $exploded_str = explode($delimiter, $input);
        $exploded_str_camel = array_map('ucwords', $exploded_str);

        return  implode("", $exploded_str_camel);
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
                $model = new $model_class();
                $model->fromArray($item);
                $ret->add($model);
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
