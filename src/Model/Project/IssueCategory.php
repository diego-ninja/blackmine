<?php

declare(strict_types=1);

namespace Blackmine\Model\Project;

use Blackmine\Model\FetchableInterface;
use Blackmine\Model\NamedIdentity;
use Blackmine\Model\User\User;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\RenameKeyMutation;

/**
 * @method void setProject(Project $project)
 * @method void setAssignedTo(User $user)
 * @method void setReassignTo(User $user)
 *
 * @method Project getProject()
 * @method User|null getAssignedTo()
 * @method User|null getReassignTo()
 */
class IssueCategory extends NamedIdentity implements FetchableInterface, MutableInterface
{
    public const ENTITY_NAME = "issue_category";

    protected static array $payload_mutations = [
        "assigned_to" => "assigned_to_id",
        "reassign_to" => "reassign_to_id"
    ];

    protected Project $project;
    protected ?User $assigned_to;
    protected ?User $reassign_to;

    public function getMutations(): array
    {
        return [
            "assigned_to" => [RenameKeyMutation::class => ["assigned_to_id"]],
            "reassign_to" => [RenameKeyMutation::class => ["reassign_to_id"]],
        ];
    }
}