<?php

declare(strict_types=1);

namespace Blackmine\Model\Project;

use Blackmine\Model\Issue\Status;
use Blackmine\Model\NamedIdentity;

/**
 * @method void setStatus(Status $status)
 * @method void setDescription(string $description)
 * @method void setEnabledStandardFields(array $enabled_standard_fields)
 *
 * @method Status getStatus()
 * @method string getDescription()
 * @method array getEnabledStandardFields()
 */
class Tracker extends NamedIdentity
{
    public const ENTITY_NAME = "tracker";

    protected Status $default_status;
    protected string $description;
    protected array $enabled_standard_fields;
}
