<?php

declare(strict_types=1);

namespace Blackmine\Model\Issue;

use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Identity;
use Blackmine\Repository\Issues\Relations;

/**
 * @method void setIssueId(int $issue_id)
 * @method void setIssueToId(int $issue_to_id)
 * @method void setRelationType(string $relation_type)
 *
 * @method int getIssueId()
 * @method int getIssueToId()
 * @method string getRelationType()
 */
class Relation extends Identity implements FetchableInterface
{
    public const ENTITY_NAME = "relation";

    public const RELATION_TYPE_RELATES = "relates";
    public const RELATION_TYPE_DUPLICATES = "duplicates";
    public const RELATION_TYPE_DUPLICATED = "duplicated";
    public const RELATION_TYPE_BLOCKS = "blocks";
    public const RELATION_TYPE_BLOCKED = "blocked";
    public const RELATION_TYPE_PRECEDES = "precedes";
    public const RELATION_TYPE_FOLLOWS = "follows";
    public const RELATION_TYPE_COPIED_TO = "copied_to";
    public const RELATION_TYPE_COPIED_FROM = "copied_from";

    protected int $issue_id;
    protected int $issue_to_id;
    protected string $relation_type = self::RELATION_TYPE_RELATES;

    public static function getRepositoryClass(): ?string
    {
        return Relations::class;
    }
}
