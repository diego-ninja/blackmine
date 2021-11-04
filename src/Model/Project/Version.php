<?php

declare(strict_types=1);

namespace Blackmine\Model\Project;

use Carbon\CarbonImmutable;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\NamedIdentity;

class Version extends NamedIdentity implements FetchableInterface
{
    public const ENTITY_NAME = "version";

    protected Project $project;
    protected string $description;
    protected string $status;
    protected CarbonImmutable $due_date;
    protected float $estimated_hours;
    protected float $spent_hours;
    protected string $sharing;
    protected ?string $wiki_page_title;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;



}