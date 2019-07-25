<?php declare(strict_types=1);

namespace ShortenEndpoint\Services;

use Hpatoio\Bitly\Client;
use Rebrandly\Service\Link;
use ShortenEndpoint\Interfaces\CacheInterface;
use ShortenEndpoint\Interfaces\ServiceInterface;

class ServiceFactory
{
    private const BITLY = 'bitly';

    private const REBRANDLY = 'rebrandly';

    /**
     * Create a service object depending on the type.
     *
     * @param mixed $type
     *
     * @return ServiceInterface
     */
    public function create($type = null): ServiceInterface
    {
        $type = is_null($type) ? $type : strtolower($type);

        switch ($type) {
            case self::REBRANDLY:
                return $this->getRebrandlyClient();
                break;
            case self::BITLY:
            default:
                return $this->getBitlyClient();
                break;
        }
    }

    /**
     * Get names of available third party services.
     *
     * @return array
     */
    public function getAvailableServices(): array
    {
        return [
            self::BITLY,
            self::REBRANDLY,
        ];
    }

    /**
     * Get a new client object for the "Rebrandy" service.
     *
     * @return ServiceInterface
     */
    private function getRebrandlyClient(): ServiceInterface
    {
        $client = new Link(getenv('REBRANDLY_TOKEN'));

        $cacheClient = $this->getCacheClient();

        return new RebrandlyServiceAdapter($client, $cacheClient);
    }

    /**
     * Get a new client object for the "Bitly" service.
     *
     * @return ServiceInterface
     */
    private function getBitlyClient(): ServiceInterface
    {
        $client = new Client(getenv('BITLY_TOKEN'));

        $cacheClient = $this->getCacheClient();

        return new BitlyServiceAdapter($client, $cacheClient);
    }

    /**
     * Get a new client object for "Redis".
     *
     * @return CacheInterface|null
     */
    private function getCacheClient()
    {
        return new RedisCacheService();
    }
}
