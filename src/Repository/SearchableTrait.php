<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Client\ClientInterface;
use Blackmine\Exception\Api\AbstractApiException;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonException;
use Blackmine\Model\CustomField;

trait SearchableTrait
{
    protected static array $filter_params = [];
    protected static array $sort_params = [];
    protected static array $search_params = [];

    protected int $limit = RepositoryInterface::DEFAULT_LIMIT;
    protected int $offset = RepositoryInterface::DEFAULT_OFFSET;

    /**
     * Adds a supported filter to the query.
     *
     * @param string $filter_name
     * @param mixed $value
     * @return SearchableTrait|AbstractSearchableRepository|CacheableRepository
     */
    public function addFilter(string $filter_name, mixed $value): self
    {
        if ($this->isAllowed($filter_name) && $this->checkType($value, $filter_name)) {
            static::$filter_params[$filter_name] = $value;
        }

        return $this;
    }

    /**
     * Adds a custom field to filter for to the search query.
     *
     * @param CustomField $cf
     * @return SearchableTrait|AbstractRepository|CacheableRepository
     */
    public function addCustomFieldFilter(CustomField $cf): self
    {
        if ($this->isAllowed(RepositoryInterface::COMMON_FILTER_CUSTOM_FIELDS)) {
            static::$filter_params[RepositoryInterface::COMMON_FILTER_CUSTOM_FIELDS][] = $cf;
        }

        return $this;
    }

    /**
     * Adds a starting date range filter to the query.
     *
     * @param CarbonInterface $date
     * @param string $date_field
     * @return SearchableTrait|AbstractRepository|CacheableRepository
     */
    public function from(CarbonInterface $date, string $date_field = self::COMMON_FILTER_UPDATED_ON): self
    {
        static::$filter_params[$date_field][RepositoryInterface::SEARCH_PARAM_FROM] = $date;
        return $this;
    }

    /**
     * Adds an ending date range filter to the query.
     *
     * @param CarbonInterface $date
     * @param string $date_field
     * @return SearchableTrait|AbstractRepository|CacheableRepository
     */
    public function to(CarbonInterface $date, string $date_field = self::COMMON_FILTER_UPDATED_ON): self
    {
        static::$filter_params[$date_field][RepositoryInterface::SEARCH_PARAM_TO] = $date;
        return $this;
    }

    /**
     * Adds sorting field and sorting direction to the query.
     *
     * @param string $field_name
     * @param string $direction
     * @return SearchableTrait|AbstractRepository|CacheableRepository
     */
    public function sortBy(string $field_name, string $direction = RepositoryInterface::SORT_DIRECTION_ASC): self
    {
        static::$sort_params[$field_name] = $direction;
        return $this;
    }

    /**
     * Adds limit to the query.
     *
     * @param int $limit
     * @return SearchableTrait|AbstractRepository|CacheableRepository
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Adds starting results offset to the query.
     *
     * @param int $offset
     * @return SearchableTrait|AbstractRepository|CacheableRepository
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Executes the search query.
     *
     * @return Collection
     * @throws AbstractApiException
     * @throws JsonException
     */
    public function search(): Collection
    {
        return $this->doSearch();
    }

    protected function reset(): self
    {
        static::$filter_params = [];
        return $this;
    }

    /**
     * @throws JsonException
     * @throws AbstractApiException
     */
    protected function doSearch(): Collection
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
                    $params[$parameter_name] = $this->addDateFilters($raw_value);
            }
        }

        return $params;
    }

    protected function addDateFilters(array | CarbonInterface $value): ?string
    {
        if ($value instanceof CarbonInterface) {
            return $value->format("Y-m-d");
        }

        if (isset($value[RepositoryInterface::SEARCH_PARAM_FROM], $value[RepositoryInterface::SEARCH_PARAM_TO])) {
            $from = $value[RepositoryInterface::SEARCH_PARAM_FROM]->format("Y-m-d");
            $to = $value[RepositoryInterface::SEARCH_PARAM_TO]->format("Y-m-d");
            return "><" .  $from . "|" . $to;
        }

        if (isset($value[RepositoryInterface::SEARCH_PARAM_FROM])) {
            $from = $value[RepositoryInterface::SEARCH_PARAM_FROM]->format("Y-m-d");
            return ">=" . $from;
        }

        if (isset($value[RepositoryInterface::SEARCH_PARAM_TO])) {
            $to = $value[RepositoryInterface::SEARCH_PARAM_TO]->format("Y-m-d");
            return "<=" . $to;
        }

        return null;
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

    /**
     * @return ClientInterface
     * @ignore
     */
    abstract public function getClient(): ClientInterface;

    /**
     * @return string
     * @ignore
     */
    abstract public function getEndpoint(): string;

    /**
     * @param string $endpoint
     * @param array $params
     * @return string
     * @ignore
     */
    abstract public function constructEndpointUrl(string $endpoint, array $params): string;

    /**
     * @return string
     * @ignore
     */
    abstract public function getModelClass(): string;

    /**
     * @return array
     * @ignore
     */
    abstract public function getAllowedFilters(): array;

    /**
     * @param string $relation
     * @ignore
     */
    abstract public function addRelationToFetch(string $relation): void;
}
