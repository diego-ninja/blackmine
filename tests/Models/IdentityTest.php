<?php

namespace Blackmine\Tests\Models;

use Blackmine\Model\Identity;

class IdentityTest extends AbstractModelTest
{
    public const TEST_MODEL = Identity::class;

    public function testConstructor(): void
    {
        $identity = new Identity(id: 1);
        $this->assertEquals(1, $identity->getId());
    }

    public function testFromArray(): void
    {
        $identity = (new Identity())->fromArray(["id" => 1]);
        $this->assertEquals(1, $identity->getId());
    }
}
