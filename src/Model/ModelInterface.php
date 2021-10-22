<?php

namespace Ninja\Redmine\Model;

use JsonSerializable;

interface ModelInterface extends JsonSerializable
{
    public function fromArray(array $data): self;
    public function toArray(): array;
    public function toJson(): string;
    public function getEntityName(): string;
    public function getRepositoryClass(): ?string;
}