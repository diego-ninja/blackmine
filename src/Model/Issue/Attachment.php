<?php

namespace Blackmine\Model\Issue;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\File;
use Blackmine\Repository\Attachments;
use Blackmine\Repository\Issues\Issues;

class Attachment extends File
{
    public const ENTITY_NAME = "attachment";

    public static function getRepositoryClass(): ?string
    {
        return Attachments::class;
    }

}