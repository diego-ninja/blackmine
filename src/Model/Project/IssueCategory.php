<?php

namespace Blackmine\Model\Project;

use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Issue\Assignee;
use Blackmine\Model\NamedIdentity;

class IssueCategory extends NamedIdentity implements FetchableInterface
{
    public const ENTITY_NAME = "issue_category";

    protected static array $payload_mutations = [
        "assigned_to" => "assigned_to_id",
        "reassign_to" => "reassign_to_id"
    ];

    protected Project $project;
    protected ?Assignee $assigned_to;
    protected ?Assignee $reassign_to;


}