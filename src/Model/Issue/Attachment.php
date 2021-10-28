<?php

namespace Blackmine\Model\Issue;

use Blackmine\Model\AbstractModel;
use Blackmine\Repository\Issues\Issues;

class Attachment extends AbstractModel
{
    public const ENTITY_NAME = "attachment";

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}