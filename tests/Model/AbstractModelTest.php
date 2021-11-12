<?php

namespace Blackmine\Tests\Model;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\ModelTrait;
use Codeception\Test\Unit;

abstract class AbstractModelTest extends Unit
{
    use ModelTrait;

    protected const TEST_MODEL = "undefined";

    protected array $original_construct_values = [];
    protected ?array $expected_payload;
    protected array $expected_interfaces = [];
    protected array $expected_array = [];
    protected string $expected_json;

    protected AbstractModel $testable_entity;

    /**
     * @throws \JsonException
     */
    // phpcs:ignore
    protected function _before(): void
    {
        $this->initExpectations();
    }

    public function testToArray(): void
    {
        $this->assertEquals($this->expected_array, $this->testable_entity->toArray());
    }
    /**
     * @throws \JsonException
     */
    public function testToJson(): void
    {
        $this->assertJsonStringEqualsJsonString($this->expected_json, $this->testable_entity->toJson());
    }

    public function testJsonSerialize(): void
    {
        $this->assertEquals($this->expected_array, $this->testable_entity->jsonSerialize());
    }

    public function testInterfaces(): void
    {
        foreach ($this->expected_interfaces as $expected_interface) {
            $this->assertContains(
                $expected_interface,
                class_implements($this->testable_entity),
                get_class($this->testable_entity) . " must implement " . $expected_interface
            );
        }
    }

    /**
     * @throws \JsonException
     */
    public function testJsonEncode(): void
    {
        $this->assertJsonStringEqualsJsonString(
            $this->expected_json,
            json_encode($this->testable_entity, JSON_THROW_ON_ERROR)
        );
    }

    public function testPayload(): void
    {
        if ($this->expected_payload) {
            $this->assertEquals($this->expected_payload, $this->testable_entity->getPayload());
        } else {
            $this->expectError();
            $this->expectErrorMessage(
                'Mandatory constant ENTITY_NAME not defined in model class: ' . static::TEST_MODEL
            );

            $this->testable_entity->getPayload();
        }
    }

    public function testGetters(): void
    {
        foreach ($this->original_construct_values as $key => $value) {
            $getter = $this->getGetter($key);
            $this->assertEquals($value, $this->testable_entity->$getter());
        }
    }
    /**
     * @throws \JsonException
     */
    private function initExpectations(): void
    {
        $data_dir = __DIR__ . "/../data/Model/";
        $model_data_file = $data_dir . $this->getUnqualifiedClassName() . "TestData.php";

        if (file_exists($model_data_file)) {
            $data = (require $model_data_file);
            if ($data["__payload"] !== false && is_array($data["__payload"])) {
                $this->expected_payload = $data["__payload"];
            } else {
                $this->expected_payload = null;
            }

            if (is_array($data["__construct"])) {
                $model_class = static::TEST_MODEL;
                $this->testable_entity = (new $model_class())->fromArray($data["__construct"]);
                $this->original_construct_values = $data["__construct"];
            }

            if (is_array($data["__expects"])) {
                $this->expected_array = $data["__expects"];
                $this->expected_json = json_encode($data["__expects"], JSON_THROW_ON_ERROR);
            }

            if (isset($data["__implements"])) {
                $this->expected_interfaces = $data["__implements"];
            }
        }
    }

    private function getUnqualifiedClassName(): string
    {
        $path = explode('\\', static::TEST_MODEL);
        return array_pop($path);
    }
}
