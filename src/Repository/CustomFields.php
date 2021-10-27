<?php

namespace Dentaku\Redmine\Repository;

use Dentaku\Redmine\Model\CustomField;

class CustomFields extends AbstractRepository
{
    public const API_ROOT = "custom_fields";

    protected function getModelClass(): string
    {
        return CustomField::class;
    }
}