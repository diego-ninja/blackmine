<?php

namespace Blackmine\Model\Issue;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Identity;
use Blackmine\Model\User\User;
use Blackmine\Repository\Issues\Issues;
use Doctrine\Common\Collections\ArrayCollection;

class Journal extends Identity
{
    public const ENTITY_NAME = "journal";

    protected User $user;
    protected string $notes;
    protected bool $private_notes;

    protected array $details;

    public function getRepositoryClass(): ?string
    {
        return Issues::class;
    }

}