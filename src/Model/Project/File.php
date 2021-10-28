<?php

namespace Blackmine\Model\Project;

use Carbon\CarbonImmutable;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Identity;
use Blackmine\Model\User\User;

class File extends Identity implements FetchableInterface
{
    public const ENTITY_NAME = "file";

    protected string $filename;
    protected int $filesize;
    protected string $content_type;
    protected string $description;
    protected string $content_url;
    protected User $author;
    protected Version $version;
    protected string $digest;
    protected int $downloads;

    protected string $token;

    protected CarbonImmutable $created_on;

}