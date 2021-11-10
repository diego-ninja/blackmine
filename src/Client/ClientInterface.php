<?php

declare(strict_types=1);

namespace Blackmine\Client;

use Blackmine\Client\Response\ApiResponse;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\CacheInterface;

interface ClientInterface
{
    public const REDMINE_FORMAT_JSON = "json";
    public const REDMINE_FORMAT_XML = "xml";

    public function __construct(ClientOptions $options, ?CacheInterface $cache = null);
    public function post(string $endpoint, string $body = '', array $headers = []): ApiResponse;
    public function get(string $endpoint, array $headers = []): ApiResponse;
    public function put(string $endpoint, ?string $body = null, array $headers = []): ApiResponse;
    public function delete(string $endpoint, array $headers = []): ApiResponse;
    public function getFormat(): string;
}
