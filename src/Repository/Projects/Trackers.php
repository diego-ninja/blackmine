<?php

namespace Dentaku\Redmine\Repository\Projects;

use Dentaku\Redmine\Model\Project\Tracker;
use Dentaku\Redmine\Repository\AbstractRepository;

class Trackers extends AbstractRepository
{
    public const API_ROOT = "trackers";

    protected function getModelClass(): string
    {
        return Tracker::class;
    }
}