<?php

declare(strict_types=1);

namespace Blackmine\Client\Response;

use JsonException;
use Requests_Response;

class ApiResponse
{
    protected bool $is_cached;

    private function __construct(protected int | bool $status, protected ?array $data)
    {
    }

    /**
     * @throws JsonException
     */
    public static function fromRequestsResponse(Requests_Response $response, bool $is_cached = false): self
    {
        $data = ($response->body !== '' && str_contains((string) $response->headers->getValues("Content-Type")[0], "application/json")) ?
            json_decode($response->body, true, 512, JSON_THROW_ON_ERROR) : [];

        $ret = new self(
            $response->status_code,
            $data
        );

        $ret->setIsCached($is_cached);

        return $ret;
    }

    public function getStatus(): int|bool
    {
        return $this->status;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function isCached(): bool
    {
        return $this->is_cached;
    }

    public function isSuccess(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    public function isPaginated(): bool
    {
        return isset($this->data["limit"], $this->data["total_count"], $this->data["offset"]);
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function setIsCached(bool $is_cached): void
    {
        $this->is_cached = $is_cached;
    }

    public function getLimit(): ?int
    {
        if ($this->isPaginated()) {
            return $this->data["limit"];
        }

        return null;
    }

    public function getTotalCount(): ?int
    {
        if ($this->isPaginated()) {
            return $this->data["total_count"];
        }

        return null;
    }

    public function getOffset(): ?int
    {
        if ($this->isPaginated()) {
            return $this->data["offset"];
        }

        return null;
    }
}
