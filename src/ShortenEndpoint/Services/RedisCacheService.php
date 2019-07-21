<?php declare(strict_types=1);

namespace ShortenEndpoint\Services;

use Predis\Client;
use Predis\Connection\ConnectionException;
use ShortenEndpoint\Interfaces\CacheInterface;

class RedisCacheService implements CacheInterface
{
    private $client;

    public function __construct()
    {
        // in order for php container to connect to redis container
        // the docker compose service name must be provided
        $this->client = new Client([
            'host' => 'redis',
        ]);
    }

    public function set($key, $value): void
    {
        $this->client->set($key, $value);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    /**
     * Check if Redis server is running.
     *
     * @return bool
     */
    public function checkConnection(): bool
    {
        try {
            $this->client->connect();
        } catch (ConnectionException $exception) {
            return false;
        }

        return true;
    }
}
