<?php

namespace Blackmine\Model\Project;

use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Identity;
use Blackmine\Model\User\User;
use Carbon\CarbonImmutable;

/**
 * @method setFilename(string $filename): void
 * @method setToken(string $token): void
 * @method setVersion(Version $version): void
 * @method setDescription(string $description): void
 *
 * @method string getFilename()
 * @method int getFilesize()
 * @method string getContentType()
 * @method string getDescription()
 * @method string getContentUrl()
 * @method User getUser()
 * @method Version getVersion()
 * @method string getDigest()
 * @method int getDownloads()
 * @method CarbonImmutable getCreatedOn()
 */
class File extends Identity implements FetchableInterface
{
    public const ENTITY_NAME = "file";

    protected string $filename;
    protected int $filesize;
    protected string $content_type;
    protected string $description;
    protected string $content_url;
    protected User $author;
    protected ?Version $version;
    protected string $digest;
    protected int $downloads;

    protected string $token;

    protected CarbonImmutable $created_on;

}