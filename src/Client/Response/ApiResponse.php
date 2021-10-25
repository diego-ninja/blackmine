<?php

namespace Ninja\Redmine\Client\Response;

class ApiResponse
{
    protected int $status;
    protected ?array $data;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function isSuccess(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }
}