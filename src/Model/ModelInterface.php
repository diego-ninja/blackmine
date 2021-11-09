<?php

declare(strict_types=1);

namespace Blackmine\Model;

use JsonSerializable;

interface ModelInterface extends JsonSerializable
{
    public function fromArray(array $data): self;
    public function toArray(): array;
    public function toJson(): string;
    public function getEntityName(): string;
    public static function getRepositoryClass(): ?string;
}
