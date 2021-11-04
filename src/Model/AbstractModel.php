<?php

declare(strict_types=1);

namespace Blackmine\Model;

use Blackmine\Mutator\ModelMutator;
use Blackmine\Mutator\MutableInterface;
use Carbon\CarbonImmutable;
use Error;
use JsonException;
use Blackmine\Collection\IdentityCollection;

abstract class AbstractModel implements ModelInterface
{
    use ModelTrait;

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
                $new_key = $aux[count($aux) - 1];

                $getter = $this->getGetter($new_key);
                $value = $this->$getter();

                if ($value instanceof Identity) {
                    $new_key .= "_id";
                    $value = $value->getId();
                }

                if ($value instanceof CarbonImmutable) {
                    $value = $value->format("Y-m-d");
                }

                if ($value instanceof IdentityCollection) {
                    $value = $value->toArray();
                }

                $ret[$new_key] = $value instanceof ModelInterface ? $value->toArray() : $value;
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

        if ($this->isMutable()) {
            $mutator = new ModelMutator($this);
            $payload = $mutator->mutate();
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

    public function isPersisted(): bool
    {
        if (property_exists($this, "id")) {
            return $this->id !== null;
        }

        return false;
    }

    public function isMutable(): bool
    {
        return in_array(MutableInterface::class, class_implements($this), true);
    }

}