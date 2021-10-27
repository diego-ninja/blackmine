<?php

namespace Dentaku\Redmine\Model;

/**
 * @method int getId()
 * @method setId(int $id): void
 */
class Identity extends AbstractModel
{
    public function __construct(protected ?int $id = null)
    {
    }

    public function getRepositoryClass(): ?string
    {
        return null;
    }

}