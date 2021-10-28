<?php

namespace Blackmine\Repository\Projects;

use Blackmine\Model\Project\Tracker;
use Blackmine\Repository\AbstractRepository;

class Trackers extends AbstractRepository
{
    public const API_ROOT = "trackers";

    protected function getModelClass(): string
    {
        return Tracker::class;
    }
}