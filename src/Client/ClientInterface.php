<?php

namespace Blackmine\Client;

use Blackmine\Client\Response\ApiResponse;
use Psr\Cache\CacheItemPoolInterface;

interface ClientInterface
{
    public const REDMINE_FORMAT_JSON = "json";
    public const REDMINE_FORMAT_XML = "xml";

    public function __construct(ClientOptions $options, ?CacheItemPoolInterface $cache = null);
    public function post(string $endpoint, string $body = '', array $headers = []): ApiResponse;
    public function get(string $endpoint, array $headers = []): ApiResponse;
    public function put(string $endpoint, ?string $body = null, array $headers = []): ApiResponse;
    public function delete(string $endpoint, array $headers = []): ApiResponse;
}