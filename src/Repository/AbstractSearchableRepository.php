<?php

namespace Blackmine\Repository;

use Blackmine\Client\ClientInterface;
use Blackmine\Client\ClientOptions;
use JsonException;

abstract class AbstractSearchableRepository extends AbstractRepository implements SearchableRepositoryInterface
{
    use SearchableTrait;

    /**
     * @param ClientInterface $client
     * @param array|array[] $options
     */
    public function __construct(
        protected ClientInterface $client,
        protected array $options = [
            ClientOptions::CLIENT_OPTION_REQUEST_HEADERS => []
        ]
    ) {
        parent::__construct($this->client, $this->options);
        $this->reset();
    }

}
