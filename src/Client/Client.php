<?php

namespace Ninja\Redmine\Client;

use JsonException;
use Requests;
use Requests_Response;
use Ninja\Redmine\Repository\AbstractRepository;
use Ninja\Redmine\Repository\Issues\Issues;
use Ninja\Redmine\Repository\Projects\Projects;
use Ninja\Redmine\Repository\Users\Users;

class Client implements ClientInterface
{
    public const REDMINE_API_KEY_HEADER = "X-Redmine-API-Key";
    public const REDMINE_IMPERSONATE_HEADER = "X-Redmine-Switch-Users";

    public function __construct(
        protected string $base_url,
        protected string $api_key,
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
    public function post(string $endpoint, string $body = '', array $headers = []): array
    {
        $response = Requests::post($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers), $body);
        return json_decode($response->body, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function get(string $endpoint, array $headers = []): array
    {
        $response = Requests::get($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers));
        echo($response->body);
        return json_decode($response->body, true, 512, JSON_THROW_ON_ERROR);
    }

    public function put(string $endpoint, ?string $body = null, array $headers = []): Requests_Response
    {
        return Requests::put($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers), $body);
    }

    public function delete(string $endpoint, array $headers = []): Requests_Response
    {
        return Requests::delete($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers));
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
    }

}