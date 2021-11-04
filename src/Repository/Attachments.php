<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Model\Issue\Attachment;

class Attachments extends AbstractRepository
{

    protected function getModelClass(): string
    {
        return Attachment::class;
    }
}