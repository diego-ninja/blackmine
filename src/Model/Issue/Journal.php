<?php

declare(strict_types=1);

namespace Blackmine\Model\Issue;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Identity;
use Blackmine\Model\User\User;
use Blackmine\Repository\Issues\Issues;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method void setUser(User $user)
 * @method void setNotes(string $notes)
 * @method void setPrivateNotes(bool $private_notes)
 * @method void setDetails(array $details)
 *
 * @method User getUser()
 * @method string getNotes()
 * @method bool isPrivateNotes()
 * @method array getDetails()
 */
class Journal extends Identity
{
    public const ENTITY_NAME = "journal";

    protected User $user;
    protected string $notes;
    protected bool $private_notes;

    protected array $details;

    public static function getRepositoryClass(): ?string
    {
        return Issues::class;
    }
}
