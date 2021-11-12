<?php

declare(strict_types=1);

namespace Blackmine\Client;

use Error;

class ClientOptions
{
    public const CLIENT_OPTION_BASE_URL = "base_url";
    public const CLIENT_OPTION_API_KEY = "api_key";
    public const CLIENT_OPTION_FORMAT = "format";
    public const CLIENT_OPTION_REQUEST_HEADERS = "headers";
    public const CLIENT_OPTIONS_ACTING_AS = "acting_as";
    public const CLIENT_OPTIONS_CACHE_TTL = "cache_ttl";

    public const CACHE_DEFAULT_TTL = 3600;

    public const REDMINE_API_KEY_HEADER = "X-Redmine-API-Key";
    public const REDMINE_IMPERSONATE_HEADER = "X-Redmine-Switch-User";

    protected static array $mandatory_options = [
        self::CLIENT_OPTION_BASE_URL,
        self::CLIENT_OPTION_API_KEY
    ];

    protected static array $default_headers = [
        "Content-Type" => "application/json",
        "User-Agent" => "Blackmine API Client v1.0",
        "Cache-Control" => "no-cache"
    ];

    public function __construct(protected array $options)
    {
        if ($this->validate()) {
            $this->initHeaders();
            $this->initFormat();
        }
    }

    public function get(string $option): mixed
    {
        return $this->options[$option] ?? null;
    }

    protected function validate(): bool
    {
        foreach (self::$mandatory_options as $mandatory_option) {
            if (!array_key_exists($mandatory_option, $this->options)) {
                throw new Error("Mandatory option " . $mandatory_option . " not found in options");
            }
        }

        return true;
    }

    protected function initHeaders(): void
    {
        $this->options[self::CLIENT_OPTION_REQUEST_HEADERS][self::REDMINE_API_KEY_HEADER] =
            $this->options[self::CLIENT_OPTION_API_KEY];
        $this->options[self::CLIENT_OPTION_REQUEST_HEADERS][self::REDMINE_IMPERSONATE_HEADER] =
            $this->options[self::CLIENT_OPTIONS_ACTING_AS] ?? null;
        $this->options[self::CLIENT_OPTION_REQUEST_HEADERS] = array_merge(
            self::$default_headers,
            $this->options[self::CLIENT_OPTION_REQUEST_HEADERS]
        );
    }

    protected function initFormat(): void
    {
        if (!isset($this->options[self::CLIENT_OPTION_FORMAT])) {
            $this->options[self::CLIENT_OPTION_FORMAT] = ClientInterface::REDMINE_FORMAT_JSON;
        }
    }
}
