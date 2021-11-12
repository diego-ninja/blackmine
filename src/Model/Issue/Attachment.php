<?php

declare(strict_types=1);

namespace Blackmine\Model\Issue;

use Blackmine\Model\Project\File;
use Blackmine\Repository\Attachments;

class Attachment extends File
{
    public const ENTITY_NAME = "attachment";

    public static function getRepositoryClass(): ?string
    {
        return Attachments::class;
    }
}
