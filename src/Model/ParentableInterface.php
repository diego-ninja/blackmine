<?php

namespace Blackmine\Model;

use Doctrine\Common\Collections\Collection;

interface ParentableInterface extends ModelInterface
{
    public function getParent(): ?ParentableInterface;
    public function setParent(ParentableInterface | array $parent): void;
    public function getChildren(): Collection;
    public function addChild(ParentableInterface $child): void;
    public function removeChild(ParentableInterface $child): void;
}