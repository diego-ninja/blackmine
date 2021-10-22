<?php

namespace Ninja\Redmine\Model;

use Carbon\CarbonImmutable;
use Error;
use JsonException;
use Ninja\Redmine\Collection\IdentityCollection;

abstract class AbstractModel implements ModelInterface
{
    use ModelTrait;

    protected static array $payload_mutations;

    public function fromArray(array $data): self
    {
        foreach ($data as $key => $value) {
            $setter = $this->getSetter($key);
            $this->$setter($value);
        }

        return $this;
    }

    public function toArray(): array
    {
        $clone = (array) $this;
        $ret = [];

        foreach ($clone as $key => $value) {
            if ($value) {
                $aux = explode("\0", $key);
                $newkey = $aux[count($aux) - 1];

                if ($value instanceof Identity) {
                    $newkey .= "_id";
                    $value = $value->getId();
                }

                if ($value instanceof CarbonImmutable) {
                    $value = $value->format("Y-m-d");
                }

                if ($value instanceof IdentityCollection) {
                    $value = $value->toArray();
                }

                $ret[$newkey] = $value instanceof ModelInterface ? $value->toArray() : $value;
            }
        }

        return $ret;
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }


    public function jsonSerialize(): ?array
    {
        return $this->toArray();
    }

    public function getPayload(): array
    {
        $payload = $this->toArray();

        foreach ($payload as $key => $value) {
            if (array_key_exists($key, static::$payload_mutations)) {
                $payload[static::$payload_mutations[$key]] = $value;
                unset($payload[$key]);
            }
        }

        return [$this->getEntityName() => $payload];
    }

    public function getEntityName(): string
    {
        if (defined('static::ENTITY_NAME')) {
            return static::ENTITY_NAME;
        }

        throw new Error('Mandatory constant ENTITY_NAME not defined in model class: ' . get_class($this));
    }

}