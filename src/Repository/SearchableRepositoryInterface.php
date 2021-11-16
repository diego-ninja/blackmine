<?php

namespace Blackmine\Repository;

use Blackmine\Model\CustomField;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\Collection;

interface SearchableRepositoryInterface extends RepositoryInterface
{
    public function addFilter(string $filter_name, mixed $value): SearchableRepositoryInterface;
    public function addCustomFieldFilter(CustomField $cf): SearchableRepositoryInterface;
    public function from(
        CarbonInterface $date,
        string $date_field = self::COMMON_FILTER_UPDATED_ON
    ): SearchableRepositoryInterface;
    public function to(
        CarbonInterface $date,
        string $date_field = self::COMMON_FILTER_UPDATED_ON
    ): SearchableRepositoryInterface;
    public function sortBy(
        string $field_name,
        string $direction = RepositoryInterface::SORT_DIRECTION_ASC
    ): SearchableRepositoryInterface;
    public function limit(int $limit): SearchableRepositoryInterface;
    public function offset(int $offset): SearchableRepositoryInterface;
    public function search(): Collection;
}