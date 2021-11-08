<?php

declare(strict_types=1);

namespace Blackmine\Exception\Api;


class UnauthorizedApiException extends AbstractApiException
{
    public const ERROR_CODE = 401;
    public const ERROR_MESSAGE= "Authorization error. Wrong api key.";
}