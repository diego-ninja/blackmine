<?php

namespace Dentaku\Redmine\Client\Response;

use JsonException;
use Requests_Response;

class ApiResponse
{
    private function __construct(protected  int $status, protected ?array $data)
    {

    }

    /**
     * @throws JsonException
     */
    public static function fromRequestsResponse(Requests_Response $response): self
    {
        $data = ($response->body !== '' && str_contains( (string) $response->headers->getValues("Content-Type")[0], "application/json")) ?
            json_decode($response->body, true, 512, JSON_THROW_ON_ERROR) :
            [];

        return new self(
            $response->status_code,
            $data
        );
    }

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