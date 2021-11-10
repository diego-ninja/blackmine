<?php

declare(strict_types=1);

namespace Blackmine\Exception\Api;

use Blackmine\Client\Response\ApiResponse;
use Error;
use Exception;
use Throwable;

abstract class AbstractApiException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        $message = $message === "" ? $this->getErrorMessage() : $message;
        $code = $code === 0 ? $this->getErrorCode() : $code;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        if (defined('static::ERROR_CODE')) {
            return static::ERROR_CODE;
        }

        throw new Error('Mandatory constant ERROR_CODE not defined in class: ' . get_class($this));
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        if (defined('static::ERROR_MESSAGE')) {
            return static::ERROR_MESSAGE;
        }

        throw new Error('Mandatory constant ERROR_MESSAGE not defined in class: ' . get_class($this));
    }

    public static function fromApiResponse(ApiResponse $api_response, ?Throwable $previous = null): AbstractApiException | Error
    {
        return match($api_response->getStatus()) {
            401 => new UnauthorizedApiException(previous: $previous),
            403 => new InaccessibleResourceException(previous: $previous),
            404 => new EntityNotFoundException(previous: $previous),
            422 => new MalformedPayloadException(previous: $previous, errors: $api_response->getData()),
            default => new Error(message: "Undefined error", code: 1000, previous: $previous),
        };
    }
}
