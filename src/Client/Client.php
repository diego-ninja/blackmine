<?php

namespace Blackmine\Client;

use Blackmine\Repository\CustomFields;
use Blackmine\Repository\Enumerations;
use Blackmine\Repository\Issues\Relations;
use Blackmine\Repository\Issues\Statuses;
use Blackmine\Repository\Projects\IssueCategories;
use Blackmine\Repository\Projects\TimeEntries;
use Blackmine\Repository\Projects\Trackers;
use Blackmine\Repository\Projects\Versions;
use Blackmine\Repository\Uploads;
use Blackmine\Repository\Users\Groups;
use Blackmine\Repository\Users\Roles;
use JsonException;
use Blackmine\Client\Response\ApiResponse;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Requests;
use Blackmine\Repository\AbstractRepository;
use Blackmine\Repository\Issues\Issues;
use Blackmine\Repository\Projects\Projects;
use Blackmine\Repository\Users\Users;

class Client implements ClientInterface
{
    public function __construct(
        protected ClientOptions $options,
        protected ?CacheItemPoolInterface $cache = null
    ) {

    }

    public function getRepository(string $endpoint): ?AbstractRepository
    {
        return match ($endpoint) {
            Issues::API_ROOT => new Issues($this),
            Users::API_ROOT => new Users($this),
            Projects::API_ROOT => new Projects($this),
            IssueCategories::API_ROOT => new IssueCategories($this),
            TimeEntries::API_ROOT => new TimeEntries($this),
            Trackers::API_ROOT => new Trackers($this),
            Statuses::API_ROOT => new Statuses($this),
            Relations::API_ROOT => new Relations($this),
            Groups::API_ROOT => new Groups($this),
            Versions::API_ROOT => new Versions($this),
            Roles::API_ROOT => new Roles($this),
            CustomFields::API_ROOT => new CustomFields($this),
            Uploads::API_ROOT => new Uploads($this),
            Enumerations::API_ROOT => new Enumerations($this),
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
     * @throws InvalidArgumentException
     */
    public function get(string $endpoint, array $headers = []): ApiResponse
    {
        $is_cached = false;
        if ($this->cache) {
            $cache_key = $this->getCacheKey(
                $this->getEndpointUrl($endpoint),
                $this->getRequestHeaders($headers)
            );

            $item = $this->cache->getItem($cache_key);
            if ($item->isHit()) {
                $response = $item->get();
                $is_cached = true;
            } else {
                $response = Requests::get($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers));
                $item->set($response);
                $item->expiresAfter($this->options->get(ClientOptions::CLIENT_OPTIONS_CACHE_TTL));
                $this->cache->save($item);
            }
        } else {
            $response = Requests::get($this->getEndpointUrl($endpoint), $this->getRequestHeaders($headers));
        }

        return ApiResponse::fromRequestsResponse($response, $is_cached);
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
        return $this->options->get(ClientOptions::CLIENT_OPTION_FORMAT);
    }

    private function getRequestHeaders(array $headers): array
    {
        if (!empty($headers)) {
            return array_merge($this->options->get(ClientOptions::CLIENT_OPTION_REQUEST_HEADERS), $headers);
        }

        return $this->options->get(ClientOptions::CLIENT_OPTION_REQUEST_HEADERS);
    }

    private function getEndpointUrl(string $endpoint): string
    {
        return $this->options->get(ClientOptions::CLIENT_OPTION_BASE_URL) . "/" . $endpoint;
    }

    private function getCacheKey(string $endpoint, array $headers = []): string
    {
        return strtoupper(sha1($endpoint . json_encode($headers, JSON_THROW_ON_ERROR)));
    }

}