<?php

namespace Blackmine\Exception\Api;

class InaccessibleResourceException extends AbstractApiException
{
    public const ERROR_CODE = 403;
    public const ERROR_MESSAGE = "Not enough privileges to access the requested resource.";
}
