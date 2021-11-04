<?php

declare(strict_types=1);

namespace Blackmine\Model;

use Blackmine\Collection\IdentityCollection;

/**
 * @method int getId()
 * @method string getName()
 *
 * @method setName(string $name): void
 * @method setId(int $id): void
 */
class NamedIdentity extends Identity
{
    public static IdentityCollection $values;

    public function __construct(protected ?int $id = null, protected ?string $name = null)
    {
    }

}