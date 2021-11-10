<?php

namespace Blackmine\Collection;

class PaginatedCollection extends IdentityCollection
{
    protected int $limit;
    protected int $total_count;
    protected int $offset;

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->total_count;
    }

    /**
     * @param int|null $total_count
     */
    public function setTotalCount(?int $total_count): void
    {
        $this->total_count = $total_count;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     */
    public function setOffset(?int $offset): void
    {
        $this->offset = $offset;
    }
}
