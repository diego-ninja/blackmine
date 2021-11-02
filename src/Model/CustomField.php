<?php

namespace Blackmine\Model;

class CustomField extends NamedIdentity
{
    public const ENTITY_NAME = "custom_field";

    protected string $customized_type;
    protected string $field_format;
    protected ?string $regexp;
    protected ?int $min_length;
    protected ?int $max_length;
    protected bool $is_required;
    protected bool $searchable;
    protected bool $multiple;
    protected ?string $default_value;
    protected array $possible_values;
}