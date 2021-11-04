<?php

declare(strict_types=1);

namespace Blackmine\Mutator;

use Blackmine\Mutator\Mutation\AbstractMutation;

interface MutableInterface
{
    /**
     * @return array<AbstractMutation>
     */
    public function getMutations(): array;
}