<?php

namespace Dentaku\Redmine\Model\Project;

use Dentaku\Redmine\Model\Issue\Assignee;
use Dentaku\Redmine\Model\NamedIdentity;

class IssueCategory extends NamedIdentity
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