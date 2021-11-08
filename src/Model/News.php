<?php

namespace Blackmine\Model;

use Blackmine\Model\Project\Project;
use Blackmine\Model\User\User;
use Carbon\CarbonImmutable;

/**
 * @method void setTitle(string $title)
 * @method void setSummary(string $summary)
 * @method void setDescription(string $description)
 * @method void setProject(Project $project)
 * @method void setAuthor(User $author)
 *
 * @method string getTitle()
 * @method string getSummary()
 * @method string getDescription()
 * @method Project getProject()
 * @method User getAuthor()
 * @method CarbonImmutable getCreatedOn()
 */
class News extends Identity implements FetchableInterface
{
    public const ENTITY_NAME = "news";

    protected string $title;
    protected ?string $summary;
    protected string $description;

    protected Project $project;
    protected User $author;

    protected CarbonImmutable $created_on;
}