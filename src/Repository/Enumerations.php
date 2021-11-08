<?php

declare(strict_types=1);

namespace Blackmine\Repository;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Exception\MethodNotImplementedException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Enumeration\AbstractEnumeration;
use Blackmine\Model\Enumeration\DocumentCategory;
use Blackmine\Model\Enumeration\IssuePriority;
use Blackmine\Model\Enumeration\TimeEntryActivity;
use Doctrine\Common\Collections\ArrayCollection;
use Error;

class Enumerations extends AbstractRepository
{

    public const API_ROOT = "enumerations";

    public const ENUM_ISSUE_PRIORITIES = "issue_priorities";
    public const ENUM_TIME_ENTRY_ACTIVITIES = "time_entry_activities";
    public const ENUM_TYPE_DOCUMENT_CATEGORIES = "document_categories";

    protected static array $allowed_enums = [
        self::ENUM_ISSUE_PRIORITIES => IssuePriority::class,
        self::ENUM_TIME_ENTRY_ACTIVITIES => TimeEntryActivity::class,
        self::ENUM_TYPE_DOCUMENT_CATEGORIES => DocumentCategory::class
    ];

    public function getModelClass(): string
    {
        return AbstractEnumeration::class;
    }

    public function all(string $endpoint = null): IdentityCollection
    {
        if (array_key_exists($endpoint, self::$allowed_enums)) {
            $api_response = $this->client->get($this->getEndpoint() . "/" . $endpoint . "." . $this->client->getFormat());
            if (isset($api_response->getData()[$endpoint])) {
                $elements = [];

                foreach ($api_response->getData()[$endpoint] as $item) {
                    $object_class = self::$allowed_enums[$endpoint];
                    $object = new $object_class();
                    $object->fromArray($item);

                    $elements[] = $object;
                }

                return new IdentityCollection($elements);
            }
        }

        return new IdentityCollection();
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function create(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function search(array $params = []): ArrayCollection
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function get(mixed $id): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function update(AbstractModel $model): ?AbstractModel
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }

    /**
     * @throws MethodNotImplementedException
     */
    public function delete(AbstractModel $model): void
    {
        throw new MethodNotImplementedException("Method " . __FUNCTION__ . " not implemented for apì: " . self::API_ROOT);
    }
}