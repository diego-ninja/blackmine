<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Model\CustomField;

class CustomFields extends AbstractRepository
{
    public const API_ROOT = "custom_fields";

    public function getModelClass(): string
    {
        return CustomField::class;
    }
}