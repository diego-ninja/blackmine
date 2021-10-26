<?php

namespace Dentaku\Redmine\Model;

class NamedIdentity extends Identity
{
    public function __construct(protected ?int $id = null, protected ?string $name = null)
    {
    }
}