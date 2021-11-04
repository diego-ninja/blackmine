<?php

declare(strict_types=1);

namespace Blackmine\Model;

/**
 * @method int getId()
 * @method setId(int $id): void
 */
class Identity extends AbstractModel
{
    public function __construct(protected ?int $id = null)
    {
    }

    public static function getRepositoryClass(): ?string
    {
        return null;
    }

}