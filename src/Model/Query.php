<?php

namespace Blackmine\Model;

/**
 * @method void setIsPublic(bool $is_public)
 * @method void setProjectId(int $project_id)
 *
 * @method bool isPublic()
 * @method int getProjectId()
 */
class Query extends NamedIdentity
{
    public const ENTITY_NAME = "query";

    protected bool $is_public;
    protected int $project_id;
}
