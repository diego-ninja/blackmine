<?php

namespace Dentaku\Redmine\Model\Project;

use Dentaku\Redmine\Model\NamedIdentity;

class Tracker extends NamedIdentity
{
    public const ENTITY_NAME = "tracker";

    protected NamedIdentity $default_status;
    protected string $description;
    protected array $enabled_standard_fields;

}