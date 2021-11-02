<?php

namespace Blackmine\Repository;

use Blackmine\Model\AbstractModel;
use Blackmine\Model\Project\File;
use Blackmine\Model\Upload;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Uploads extends AbstractRepository
{

    public const API_ROOT = "uploads";

    protected function getModelClass(): string
    {
        return Upload::class;
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        if (!$model instanceof File) {
            throw new Error('Wrong model class for ' . $this->getEndpoint() . " api. Expected " . File::class);
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
                $model->fromArray($api_response->getData()["upload"]);
                $model->setFilename($filename);

                return $model;
            }

        }

        return null;
    }

    public function get(mixed $id): ?AbstractModel
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function search(array $params = []): ArrayCollection
    {
        throw new Error("Method "  . __FUNCTION__ . "not implemented for apì: " . self::API_ROOT);
    }

    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function delete(AbstractModel $model): void
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    public function all(?string $endpoint): ArrayCollection
    {
        throw new Error("Method "  . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

}