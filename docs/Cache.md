# Caching responses

Blackmine has caching capabilities, ideally you should pass a [CacheInterface](https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/Cache/CacheInterface.php) object to the client constructor and the client will start to cache all api responses.

There are two cache invalidation mechanisms available, one based in TTL expiration and another one based in item tags, if you want to use the latter one you need to pass a [TagAwareCacheInterface](https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/Cache/TagAwareCacheInterface.php) as cache adapter to the client.

Every time an api modification request (POST/PUT/DELETE) is issued, the client deletes the old cached item and generates a new one, besides that, all searches affected by this modification are removed from the cache. If you are using an adapter that doesn't support tagging you must fine tuning your TTLs in order to get fresh results, long TTLs could result in old data retrieved from the cache served as fresh data.  

```php
use Blackmine\Client\Client;
use Blackmine\Client\ClientOptions;
use Blackmine\Repository\Issues\Issues;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

$cache = new TagAwareAdapter(
    new RedisAdapter(
        RedisAdapter::createConnection("redis://localhost")
    )
);

$options = new ClientOptions([
    ClientOptions::CLIENT_OPTION_BASE_URL => "http://your.redmine.url",
    ClientOptions::CLIENT_OPTION_API_KEY => "your.redmine.api.key"
    ClientOptions::CLIENT_OPTIONS_CACHE_TTL => 3600
]);

$client = new Client($options, $cache);
$issue = $client->getRepository(Issues::API_ROOT)->get(1);

```