<?php

declare(strict_types=1);

namespace Blackmine\Exception\Api;


use Throwable;

class MalformedPayloadException extends AbstractApiException
{
    public const ERROR_CODE = 422;
    public const ERROR_MESSAGE = "Malformed request payload";

    public function __construct($message = "", $code = 0, ?Throwable $previous = null, protected ?array $errors = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}