<?php

declare(strict_types=1);

namespace Blackmine\Model\Enumeration;

use Blackmine\Model\NamedIdentity;
use Blackmine\Repository\Enumerations;

/**
 * @method bool isDefault()
 */
class AbstractEnumeration extends NamedIdentity
{
    protected bool $is_default;

    public static function getRepositoryClass(): ?string
    {
        return Enumerations::class;
    }

}