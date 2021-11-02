<?php

namespace Blackmine\Model;

use Blackmine\Repository\Uploads;

/**
 * @method string getToken()
 */
class Upload extends AbstractModel
{
    public const ENTITY_NAME = "upload";

    protected string $token;

    public static function getRepositoryClass(): ?string
    {
        return Uploads::class;
    }
}