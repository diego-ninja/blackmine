<?php

namespace Ninja\Redmine\Model\Issue;

use Ninja\Redmine\Model\AbstractModel;
use Ninja\Redmine\Repository\Issues\Issues;

class Attachment extends AbstractModel
{
    public const ENTITY_NAME = "attachment";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}