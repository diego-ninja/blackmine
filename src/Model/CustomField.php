<?php

namespace Dentaku\Redmine\Model;

class CustomField extends Identity
{
    protected mixed $value;

    public function getRepositoryClass(): ?string
    {
        return null;
    }

}