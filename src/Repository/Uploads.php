<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Exception\Api\AbstractApiException;
use Blackmine\Exception\InvalidModelException;
use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\File;
use Blackmine\Model\Upload;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Uploads extends AbstractRepository
{

    public const API_ROOT = "uploads";

    public function getModelClass(): string
    {
        return Upload::class;
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        if (!$model instanceof File) {
            throw new InvalidModelException(
                'Wrong model class for ' . $this->getEndpoint() . " api. Expected " . File::class
            );
        }

        $file = $model->getFilename();

        if (file_exists($file)) {
            $filename = basename($file);

            $api_response = $this->client->post(
                $this->getEndpoint() . "." . $this->client->getFormat() . "?filename=" . $filename,
                file_get_contents($file),
                ["Content-Type" => "application/octet-stream"]
            );

            if ($api_response->isSuccess()) {
                $model_data = $api_response->getData()["upload"] ?? null;
                if ($model_data) {
                    $model->fromArray($model_data);
                    $model->setFilename($filename);

                    return $model;
                }
            }

            throw AbstractApiException::fromApiResponse($api_response);
        }

        return null;
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function get(mixed $id): ?AbstractModel
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function search(array $params = []): ArrayCollection
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function delete(AbstractModel $model): void
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function all(?string $endpoint = null): ArrayCollection
    {
        throw new MethodNotImplementedException(
            "Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT
        );
    }
}
