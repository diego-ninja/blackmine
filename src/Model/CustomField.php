<?php

namespace Dentaku\Redmine\Model;

class CustomField extends NamedIdentity
{
    protected mixed $value;
    protected bool $multiple;
}