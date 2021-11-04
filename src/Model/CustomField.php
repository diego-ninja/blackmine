<?php

declare(strict_types=1);

namespace Blackmine\Model;

/**
 * @method void setValue(mixed $value)
 * @method void setCustomizedType(string $customized_type)
 * @method void setFieldFormat(string $field_format)
 * @method void setRegexp(?string $regexp)
 * @method void setMinLength(?int $min_length)
 * @method void setMaxLength(?int $max_length)
 * @method void setIsRequired(bool $is_required)
 * @method void setSearchable(bool $searchable)
 * @method void setMultiple(bool $multiple)
 * @method void setDefaultValue(?string $default_value)
 * @method void setPossibleValues(array $possible_values)
 *
 * @method mixed getValue()
 * @method string getCustomizedValue()
 * @method string getFieldFormat()
 * @method string|null getRegexp()
 * @method int|null getMinLength()
 * @method int|null getMaxLength()
 * @method bool getIsRequired()
 * @method bool getSearchable()
 * @method bool getMultiple()
 * @method string|null getDefaultValue()
 * @method array getPossibleValues()
 */
class CustomField extends NamedIdentity
{
    public const ENTITY_NAME = "custom_field";

    protected mixed $value;
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