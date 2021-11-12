<?php

namespace Blackmine\Tests\Repository;

use Blackmine\Client\Client;
use Blackmine\Client\ClientInterface;
use Blackmine\Client\ClientOptions;
use Blackmine\Client\Response\ApiResponse;
use Blackmine\Model\AbstractModel;
use Codeception\Test\Unit;
use Exception;

abstract class AbstractRepositoryTest extends Unit
{
    protected const REPOSITORY_CLASS = "undefined";

    protected array $test_data;

    // phpcs:ignore
    protected function _setUp(): void
    {
        $this->initTestData();
    }


    /**
     * @throws Exception
     */
    public function testGet(): void
    {
        $test_data = $this->test_data["__methods"]["get"]["__success"] ?? null;

        if ($test_data) {
            foreach ($test_data as $id => $test_datum) {
                $repository_class = static::REPOSITORY_CLASS;
                $repository = new $repository_class($this->getClientResponding(
                    "get",
                    $this->getSuccessResponse($test_datum["__input"])
                ));
                $model = $repository->get($id);

                $this->assertInstanceOf(get_class($test_datum["__output"]), $model);
                $this->assertEquals($model, $test_datum["__output"]);
            }
        }
    }

    public function testGetThrows(): void
    {
        $this->assertThrows("get");
    }

    public function testAll(): void
    {
    }

    public function testAllThrows(): void
    {
        $this->assertThrows("all");
    }

    public function testSearch(): void
    {
    }

    public function testSearchThrows(): void
    {
        $this->assertThrows("search");
    }

    public function testCreate(): void
    {
    }

    public function testCreateThrows(): void
    {
        $this->assertThrows("create");
    }

    public function testUpdate(): void
    {
    }

    public function testUpdateThrows(): void
    {
        $this->assertThrows("update");
    }

    public function testDelete(): void
    {
    }

    public function testDeleteThrows(): void
    {
        $this->assertThrows("delete");
    }

    /**
     * @throws Exception
     */
    protected function getClientResponding(string $method, ApiResponse $response): ClientInterface
    {
        $methods_map = [
            "get" => "get",
            "all" => "get",
            "search" => "get",
            "create" => "post",
            "update" => "put",
            "delete" => "delete"
        ];

        $method = $methods_map[$method];

        return $this->construct(
            Client::class,
            [
                "options" => new ClientOptions([
                    ClientOptions::CLIENT_OPTION_BASE_URL => "",
                    ClientOptions::CLIENT_OPTION_API_KEY => ""
                ])
            ],
            [
                $method => function () use ($response) {
                    return $response;
                }
            ]
        );
    }

    protected function assertThrows(string $method): void
    {
        $test_data = $this->test_data["__methods"][$method]["__error"] ?? null;
        if (is_string($test_data)) {
            $this->assertMethodThrowsException($method, 500, $test_data);
        }

        if (is_array($test_data)) {
            foreach ($test_data as $error_code => $expected_exception) {
                $this->assertMethodThrowsException($method, $error_code, $expected_exception);
            }
        }
    }

    protected function assertMethodThrowsException(
        string $method,
        int $status_code,
        string $expected_exception
    ): void {
        $repository_class = static::REPOSITORY_CLASS;
        $repository = new $repository_class($this->getClientResponding(
            $method,
            $this->getErrorRespponse($status_code, [])
        ));

        $this->expectException($expected_exception);

        if (in_array($method, ["update", "create", "delete"])) {
            $model_class = $repository->getModelClass();
            $model = new $model_class();
            $repository->$method($model);
        } else {
            $repository->$method();
        }
    }


    protected function initTestData(): void
    {
        $data_dir = __DIR__ . "/../data/Repository/";
        $model_data_file = $data_dir . $this->getUnqualifiedClassName() . "Data.php";

        if (file_exists($model_data_file)) {
            $data = (require $model_data_file);
            $this->test_data = $data;
        }
    }

    protected function getUnqualifiedClassName(): string
    {
        $path = explode('\\', get_class($this));
        return array_pop($path);
    }

    protected function getSuccessResponse(array $data = []): ApiResponse
    {
        return new ApiResponse(200, $data);
    }

    protected function getErrorRespponse(int $error_code, array $data): ApiResponse
    {
        return new ApiResponse($error_code, $data);
    }
}
