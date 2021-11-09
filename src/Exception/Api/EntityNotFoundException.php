<?php

declare(strict_types=1);

namespace Blackmine\Exception\Api;

class EntityNotFoundException extends AbstractApiException
{
    public const ERROR_CODE = 404;
    public const ERROR_MESSAGE = "Requested entity not found.";
}
