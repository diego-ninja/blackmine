<?php

namespace Dentaku\Redmine\Client;

use JsonException;
use Dentaku\Redmine\Client\Response\ApiResponse;
use Requests;
use Dentaku\Redmine\Repository\AbstractRepository;
use Dentaku\Redmine\Repository\Issues\Issues;
use Dentaku\Redmine\Repository\Projects\Projects;
use Dentaku\Redmine\Repository\Users\Users;

class Client implements ClientInterface
{
    public const REDMINE_API_KEY_HEADER = "X-Redmine-API-Key";
    public const REDMINE_IMPERSONATE_HEADER = "X-Redmine-Switch-Users";

    public function __construct(
        protected string $base_url,
        protected string $api_key,
        protected string $format = ClientInterface::REDMINE_FORMAT_JSON,
        protected array $headers = []
    ) {
        $this->initRedmineHeaders();
    }

    public function getRepository(string $endpoint): ?AbstractRepository
    {
        return match ($endpoint) {
            Issues::API_ENDPOINT => new Issues($this),
            Users::API_ENDPOINT => new Users($this),
            Projects::API_ENDPOINT => new Projects($this),
            default => null,
        };

    }

    /**
     * @throws JsonException
     */
    public function post(string $endpoint, string $body = '', array $headers = []): ApiResponse
    {
        $response = Requests::post($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers), $body);
        return ApiResponse::fromRequestsResponse($response);
    }

    /**
     * @throws JsonException
     */
    public function get(string $endpoint, array $headers = []): ApiResponse
    {
        $response = Requests::get($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers));
        return ApiResponse::fromRequestsResponse($response);
    }

    /**
     * @throws JsonException
     */
    public function put(string $endpoint, ?string $body = null, array $headers = []): ApiResponse
    {
        $response = Requests::put($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers), $body);
        return ApiResponse::fromRequestsResponse($response);

    }

    /**
     * @throws JsonException
     */
    public function delete(string $endpoint, array $headers = []): ApiResponse
    {
        $response = Requests::delete($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers));
        return ApiResponse::fromRequestsResponse($response);
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    private function getRequestHeaders(array $headers): array
    {
        if (!empty($headers)) {
            return array_merge($this->headers, $headers);
        }

        return $this->headers;
    }

    private function getEndpointUrl(string $endpoint): string
    {
        return $this->base_url . "/" . $endpoint;
    }

    private function initRedmineHeaders(): void
    {
        $this->headers[self::REDMINE_API_KEY_HEADER] = $this->api_key;
        $this->headers["Content-Type"] = "application/json";
        $this->headers["Cache-Control"] = "no-cache";
        $this->headers["User-Agent"] = "Scalefast Gitlab Connector v1.0";
    }

}