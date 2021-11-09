<?php

namespace Blackmine\Tests\Models;

use Blackmine\Model\NamedIdentity;

class NamedIdentityTest extends AbstractModelTest
{
    public const TEST_MODEL = NamedIdentity::class;

    public function testConstructor(): void
    {
        $identity = new NamedIdentity(id: 1, name: "Test");
        $this->assertEquals(1, $identity->getId());
        $this->assertEquals("Test", $identity->getName());
    }

    public function testFromArray(): void
    {
        $identity = (new NamedIdentity())->fromArray(["id" => 1, "name" => "Test"]);
        $this->assertEquals(1, $identity->getId());
        $this->assertEquals("Test", $identity->getName());
    }

    public function testToArray(): void
    {
        $expected = ["id" => 1, "name" => "Test"];
        $identity = new NamedIdentity(1, "Test");

        $this->assertEquals($expected, $identity->toArray());

    }

    /**
     * @throws \JsonException
     */
    public function testToJson(): void
    {
        $expected = json_encode(["id" => 1, "name" => "Test"], JSON_THROW_ON_ERROR);
        $identity = new NamedIdentity(1, "Test");

        $this->assertJsonStringEqualsJsonString($expected, $identity->toJson());
    }

    public function testJsonSerialize(): void
    {
        $expected = ["id" => 1, "name" => "Test"];
        $identity = new NamedIdentity(1, "Test");

        $this->assertEquals($expected, $identity->jsonSerialize());

    }

    /**
     * @throws \JsonException
     */
    public function testJsonEncode(): void
    {
        $expected = json_encode(["id" => 1, "name" => "Test"], JSON_THROW_ON_ERROR);
        $identity = new NamedIdentity(1, "Test");

        $this->assertJsonStringEqualsJsonString($expected, json_encode($identity, JSON_THROW_ON_ERROR));

    }

    public function testPayload(): void
    {
        $identity = new NamedIdentity(1, "Test");
        $this->expectError();
        $this->expectErrorMessage('Mandatory constant ENTITY_NAME not defined in model class: ' . NamedIdentity::class);

        $identity->getPayload();
    }

}
