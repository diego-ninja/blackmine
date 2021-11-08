<?php

namespace Blackmine\Repository;

use Blackmine\Client\ClientOptions;
use Blackmine\Client\Generator\KeyGeneratorInterface;
use Blackmine\Exception\Api\AbstractApiException;
use Blackmine\Exception\InvalidModelException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use JsonException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheableRepository implements RepositoryInterface
{
    use RepositoryTrait;
    use SearchableTrait;

    public function __construct(
        protected AbstractRepository $repository,
        protected CacheInterface $cache,
        protected KeyGeneratorInterface $generator,
        protected array $options = [
            ClientOptions::CLIENT_OPTION_REQUEST_HEADERS => []
        ]
    ) {

    }

    public function actingAs(string | User $user): self
    {
        if ($user instanceof User) {
            $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS][ClientOptions::REDMINE_IMPERSONATE_HEADER] = $user->getLogin();
        } else {
            $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS][ClientOptions::REDMINE_IMPERSONATE_HEADER] = $user;
        }

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(mixed $id): ?AbstractModel
    {
        $cache_key = $this->generator->generate($this->repository->getModelClass(), $id);
        return $this->cache->get($cache_key, function (ItemInterface $item) use ($id) {
            $model = $this->repository->get($id);
            if ($model) {
                $item->set($model);
                $item->expiresAfter($this->getCacheTTL());
                $item->tag([$model->getEntityName()]);
            }

            return $model;

        });
    }

    /**
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws AbstractApiException
     * @throws InvalidModelException
     */
    public function create(AbstractModel $model): ?AbstractModel
    {
        $model = $this->repository->create($model);
        if ($model?->isCacheable()) {
            return $this->cacheModel($model);
        }

        return $model;

    }

    /**
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws AbstractApiException
     * @throws InvalidModelException
     */
    public function update(AbstractModel $model): ?AbstractModel
    {
        $model = $this->repository->update($model);
        if ($model?->isCacheable()) {
            return $this->cacheModel($model);
        }

        return $model;
    }

    /**
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws AbstractApiException
     * @throws InvalidModelException
     */
    public function delete(AbstractModel $model): void
    {
        $this->repository->delete($model);
        if ($model->isCacheable()) {
            $cache_key = $this->generator->generate($model->getEntityName(), $model->getId());
            $this->cache->delete($cache_key);
            if ($this->supportsTagging()) {
                $this->cache->invalidateTags([$model->getEntityName() . "_search_results"]);
            }
        }
    }

    /**
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function search(): ArrayCollection
    {
        $cache_key = $this->generator->generate(
            $this->repository->getEndpoint(),
            json_encode(static::$filter_params, JSON_THROW_ON_ERROR)
        );

        return $this->cache->get($cache_key, function (ItemInterface $item) {
            $search_results = $this->doSearch();
            if (!$search_results->isEmpty()) {
                $item->set($search_results);
                $item->expiresAfter($this->getCacheTTL());
                if ($this->supportsTagging()) {
                    $item->tag([$search_results->first()->getEntityName() . "_search_results"]);
                }
            }

            return $search_results;
        });

    }

    public function getAllowedFilters(): array
    {
        return $this->repository->getAllowedFilters();
    }

    public function getRelationClassMap(): array
    {
        return $this->repository->getRelationClassMap();
    }

    public function __call(string $method, array $args): mixed
    {
        if (method_exists($this->repository, $method)) {
            return call_user_func_array([$this->repository, $method], $args);
        }

        return null;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function cacheModel(AbstractModel $model): ?AbstractModel
    {
        $cache_key = $this->generator->generate($model->getEntityName(), $model->getId());

        $this->cache->delete($cache_key);
        return $this->cache->get($cache_key, function (ItemInterface $item) use ($model) {
            $item->set($model);
            $item->expiresAfter($this->getCacheTTL());
            if ($this->supportsTagging()) {
                $this->cache->invalidateTags([$model->getEntityName() . "_search_results"]);
                $item->tag([$model->getEntityName()]);
            }

            return $model;
        });
    }

    protected function getCacheTTL(): int
    {
        return $this->options[ClientOptions::CLIENT_OPTIONS_CACHE_TTL] ?? ClientOptions::CACHE_DEFAULT_TTL;
    }

    protected function supportsTagging(): bool
    {
        return $this->cache instanceof TagAwareCacheInterface;
    }

}