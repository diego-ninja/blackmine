<?php

declare(strict_types=1);

namespace Blackmine\Tests\Model\Issue;

use Blackmine\Model\Issue\Status;
use Blackmine\Tests\Model\AbstractModelTest;

class StatusTest extends AbstractModelTest
{
    public const TEST_MODEL = Status::class;

    public function testClose()
    {
        $this->testable_entity->close();
        $this->assertTrue($this->testable_entity->isClosed());
    }
}
