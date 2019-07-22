<?php declare(strict_types=1);

namespace ShortenEndpoint\Services;

use ShortenEndpoint\Interfaces\CacheInterface;

abstract class ServiceAdapterBase
{
    /**
     * @var CacheInterface|null $cache
     */
    protected $cache;

    /**
     * ServiceAdapterBase constructor.
     *
     * @param CacheInterface|null $cache
     */
    public function __construct(?CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Check if the specific url has already been cached.
     *
     * @param $key
     *
     * @return array|boolean
     */
    protected function getCachedResponse($key)
    {
        if (is_null($this->cache) || ! $this->cache->checkConnection() || !$key) {
            return false;
        }

        $cachedResponse = $this->cache->get($key);

        return ! is_null($cachedResponse) ? json_decode($cachedResponse, true) : false;
    }

    /**
     * If cache has been set, store the response from the service.
     *
     * @param array $data
     */
    protected function setCacheResponse(array $data): void
    {
        if (is_null($this->cache) || ! $this->cache->checkConnection()) {
            return;
        }

        $json = json_encode($data);

        $this->cache->set($data['longUrl'], $json);
    }

    protected abstract function formatResponse(array $data): array;
}
