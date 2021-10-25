<?php

namespace Dentaku\Redmine\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Dentaku\Redmine\Model\AbstractModel;
use Dentaku\Redmine\Model\CustomField;

interface RepositoryInterface
{
    public const SEARCH_PARAM_LIMIT = "limit";
    public const SEARCH_PARAM_OFFSET = "offset";
    public const SEARCH_PARAM_SORT = "sort";
    public const SEARCH_PARAM_INCLUDE = "include";
    public const SEARCH_PARAM_FROM = "from";
    public const SEARCH_PARAM_TO = "to";

    public const SEARCH_PARAM_TYPE_INT = "integer";
    public const SEARCH_PARAM_TYPE_STRING = "string";
    public const SEARCH_PARAM_TYPE_BOOL = "boolean";
    public const SEARCH_PARAM_TYPE_INT_ARRAY = "integer[]";
    public const SEARCH_PARAM_TYPE_STRING_ARRAY = "string[]";
    public const SEARCH_PARAM_TYPE_CF_ARRAY = CustomField::class . "[]";

    public const COMMON_FILTER_CUSTOM_FIELDS = "custom_fields";
    public const COMMON_FILTER_CREATED_ON = "created_on";
    public const COMMON_FILTER_UPDATED_ON = "updated_on";

    public const SORT_DIRECTION_ASC = "asc";
    public const SORT_DIRECTION_DESC = "desc";

    public const DEFAULT_LIMIT = 25;
    public const DEFAULT_OFFSET = 0;

    public function get(mixed $id): ?AbstractModel;
    public function create(AbstractModel $model): ?AbstractModel;
    public function update(AbstractModel $model): ?AbstractModel;
    public function delete(AbstractModel $model): void;
    public function search(array $params = []): ArrayCollection;

}