<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Client\ClientInterface;
use Blackmine\Exception\Api\AbstractApiException;
use Carbon\CarbonInterface;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\Identity;
use Doctrine\Common\Collections\ArrayCollection;
use Blackmine\Model\CustomField;

trait SearchableTrait
{
    protected static array $filter_params = [];
    protected static array $sort_params = [];
    protected static array $search_params = [];

    protected int $limit = RepositoryInterface::DEFAULT_LIMIT;
    protected int $offset = RepositoryInterface::DEFAULT_OFFSET;

    protected array $fetch_relations = [];

    public function addFilter(string $filter_name, mixed $value): self
    {
        if ($this->isAllowed($filter_name) && $this->checkType($value, $filter_name)) {
            static::$filter_params[$filter_name] = $value;
        }

        return $this;
    }

    public function addCustomFieldFilter(CustomField $cf): self
    {
        if ($this->isAllowed(RepositoryInterface::COMMON_FILTER_CUSTOM_FIELDS)) {
            static::$filter_params[RepositoryInterface::COMMON_FILTER_CUSTOM_FIELDS][] = $cf;
        }

        return $this;
    }


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

    public function reset(): self
    {
        static::$filter_params = [];
        return $this;
    }

    public function from(CarbonInterface $date, string $date_field = self::COMMON_FILTER_UPDATED_ON): self
    {
        static::$filter_params[RepositoryInterface::SEARCH_PARAM_FROM][$date_field] = $date;
        return $this;
    }

    public function to(CarbonInterface $date, string $date_field = self::COMMON_FILTER_UPDATED_ON): self
    {
        static::$filter_params[RepositoryInterface::SEARCH_PARAM_TO][$date_field] = $date;
        return $this;
    }

    public function sortBy(string $field_name, string $direction = RepositoryInterface::SORT_DIRECTION_ASC): self
    {
        static::$sort_params[$field_name] = $direction;
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @throws AbstractApiException
     */
    protected function doSearch(): ArrayCollection
    {
        $ret = new ArrayCollection();

        $search_endpoint = $this->getEndpoint() . "." . $this->getClient()->getFormat();

        $this->sanitizeParams();
        static::$search_params = $this->normalizeParams(static::$filter_params);
        static::$search_params = $this->addOrdering(static::$search_params);
        static::$search_params = $this->addRelations(static::$search_params);

        while ($this->limit > 0) {
            if ($this->limit > 100) {
                $_limit = 100;
                $this->limit -= 100;
            } else {
                $_limit = $this->limit;
                $this->limit = 0;
            }

            static::$search_params[RepositoryInterface::SEARCH_PARAM_LIMIT] = $_limit;
            static::$search_params[RepositoryInterface::SEARCH_PARAM_OFFSET] = $this->offset;

            $api_response = $this->getClient()->get(
                $this->constructEndpointUrl($search_endpoint, static::$search_params)
            );

            if ($api_response->isSuccess()) {
                $ret = $this->getCollection($api_response->getData()[$this->getEndpoint()]);
                $this->offset += $_limit;
            } else {
                throw AbstractApiException::fromApiResponse($api_response);
            }
        }

        return $ret;
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

    protected function isValidParameter(mixed $parameter, string $parameter_name): bool
    {
        $is_valid = false !== $parameter && null !== $parameter && '' !== $parameter;

        if (!empty($this->getAllowedFilters())) {
            return $is_valid && array_key_exists($parameter_name, $this->getAllowedFilters());
        }

        return $is_valid;
    }

    protected function sanitizeParams(): array
    {
        return array_filter(
            static::$filter_params,
            [$this, 'isValidParameter'],
            ARRAY_FILTER_USE_BOTH
        );
    }

    protected function checkType(mixed $value, string $parameter_name): bool
    {
        $expected_type = $this->getAllowedFilters()[$parameter_name];

        if (is_object($value)) {
            return get_class($value) === $expected_type;
        }

        if (is_array($value)) {
            return $this->isArrayType($expected_type) && $this->isValidArray($value, substr($expected_type, 0, -2));
        }

        return gettype($value) === $expected_type;
    }

    protected function normalizeParams(array $raw_params): array
    {
        $params = [];
        foreach ($raw_params as $parameter_name => $raw_value) {
            switch ($this->getAllowedFilters()[$parameter_name]) {
                case RepositoryInterface::SEARCH_PARAM_TYPE_INT:
                case RepositoryInterface::SEARCH_PARAM_TYPE_STRING:
                case RepositoryInterface::SEARCH_PARAM_TYPE_BOOL:
                default:
                    $params[$parameter_name] = $raw_value;
                    break;
                case RepositoryInterface::SEARCH_PARAM_TYPE_INT_ARRAY:
                case RepositoryInterface::SEARCH_PARAM_TYPE_STRING_ARRAY:
                    $params[$parameter_name] = implode(",", $raw_value);
                    break;
                case RepositoryInterface::SEARCH_PARAM_TYPE_CF_ARRAY:
                    foreach ($raw_value as $cf) {
                        $params["cf_" . $cf->getId()] = $cf->getValue();
                    }
                    break;
                case CarbonInterface::class:
                    $params[$parameter_name] = $raw_value->format("Y-m-d");
            }
        }

        return $params;
    }

    protected function addOrdering(array $params): array
    {
        if (!empty(static::$sort_params)) {
            $ordering = [];
            foreach (static::$sort_params as $field => $direction) {
                if ($direction === RepositoryInterface::SORT_DIRECTION_DESC) {
                    $ordering[] = $field . ":" . $direction;
                } else {
                    $ordering[] = $field;
                }
            }

            $params[RepositoryInterface::SEARCH_PARAM_SORT] = implode(",", $ordering);
        }

        return $params;
    }

    protected function addRelations(array $params): array
    {
        if (!empty($this->fetch_relations)) {
            $params["include"] = implode(",", $this->fetch_relations);
        }

        return $params;
    }


    protected function isAllowed(string $filter_name): bool
    {
        return array_key_exists($filter_name, $this->getAllowedFilters());
    }

    protected function isArrayType(string $type): bool
    {
        return str_ends_with($type, "[]");
    }

    protected function isValidArray(array $data, string $expected_type): bool
    {
        $is_valid = true;
        foreach ($data as $value) {
            if (gettype($value) !== $expected_type) {
                return false;
            }
        }

        return $is_valid;
    }

    abstract public function getClient(): ClientInterface;
    abstract public function getEndpoint(): string;
    abstract public function constructEndpointUrl(string $endpoint, array $params): string;
    abstract public function getModelClass(): string;
    abstract public function getAllowedFilters(): array;
    abstract public function addRelationToFetch(string $relation): void;
}
