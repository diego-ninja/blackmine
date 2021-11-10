<?php

declare(strict_types=1);

namespace Blackmine\Mutator;

use Blackmine\Model\ModelInterface;

interface MutableInterface extends ModelInterface
{
    public function getMutations(): array;
    public function isMutable(): bool;
}
