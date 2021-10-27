<?php

namespace Dentaku\Redmine\Model;

/**
 * @method int getId()
 * @method string getName()
 *
 * @method setName(string $name): void
 * @method setId(int $id): void
 */
class NamedIdentity extends Identity
{
    public function __construct(protected ?int $id = null, protected ?string $name = null)
    {
    }
}