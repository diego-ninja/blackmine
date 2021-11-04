<?php

declare(strict_types=1);

namespace Blackmine\Model\Project;

use Blackmine\Model\NamedIdentity;

class Tracker extends NamedIdentity
{
    public const ENTITY_NAME = "tracker";

    protected NamedIdentity $default_status;
    protected string $description;
    protected array $enabled_standard_fields;

}