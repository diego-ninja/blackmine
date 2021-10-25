<?php

namespace Ninja\Redmine\Client;

use Ninja\Redmine\Client\Response\ApiResponse;

interface ClientInterface
{
    public const REDMINE_FORMAT_JSON = "json";
    public const REDMINE_FORMAT_XML = "xml";

    public function post(string $endpoint, string $body = '', array $headers = []): ApiResponse;
    public function get(string $endpoint, array $headers = []): ApiResponse;
    public function put(string $endpoint, ?string $body = null, array $headers = []): ApiResponse;
    public function delete(string $endpoint, array $headers = []): ApiResponse;
}