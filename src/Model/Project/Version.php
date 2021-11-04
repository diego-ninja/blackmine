<?php

declare(strict_types=1);

namespace Blackmine\Model\Project;

use Carbon\CarbonImmutable;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\NamedIdentity;

/**
 * @method void setProject(Project $project)
 * @method void setDescription(string $description)
 * @method void setStatus(string $status)
 * @method void setDueDate(CarbonImmutable $due_date)
 * @method void setEstimatedHours(float $estimated_hours)
 * @method void setSpentHours(float $spent_hours)
 * @method void setSharing(string $sharing)
 * @method void setWikiPageTitle(string $wiki_page_title)
 *
 * @method Project getProject()
 * @method string getDescription()
 * @method string getStatus()
 * @method CarbonImmutable getDueDate()
 * @method float getEstimatedHours()
 * @method float getSpentHours()
 * @method string getSharing()
 * @method string getWikiPageTitle()
 */
class Version extends NamedIdentity implements FetchableInterface
{
    public const ENTITY_NAME = "version";

    public const VERSION_STATUS_OPEN = "open";
    public const VERSION_STATUS_LOCKED = "locked";
    public const VERSION_STATUS_CLOSED = "closed";

    public const VERSION_SHARING_NONE = "none";
    public const VERSION_SHARING_DESCENDANTS = "descendants";
    public const VERSION_SHARING_HIERARCHY = "hierarchy";
    public const VERSION_SHARING_TREE = "tree";
    public const VERSION_SHARING_SYSTEM = "system";

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