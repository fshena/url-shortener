<?php declare(strict_types=1);

namespace ShortenEndpoint\Services;

use Rebrandly\Service\Link;
use ShortenEndpoint\Interfaces\CacheInterface;
use ShortenEndpoint\Interfaces\ServiceInterface;

class RebrandlyServiceAdapter extends ServiceAdapterBase implements ServiceInterface
{
    /**
     * @var Link $client
     */
    private $client;

    /**
     * RebrandlyServiceAdapter constructor.
     *
     * @param Link                $client
     * @param CacheInterface|null $cache
     */
    public function __construct(Link $client, CacheInterface $cache = null)
    {
        parent::__construct($cache);

        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function shortenUrl(string $url): array
    {
        // check if the url has been used before
        $cachedResponse = $this->getCachedResponse($url);

        if ($cachedResponse) {
            return $cachedResponse;
        }

        $response = $this->client->quickCreate($url)->export();

        $response = $this->formatResponse($response);

        if (isset($response['shortUrl'])) {
            $this->setCacheResponse($response);
        }

        return $response;
    }

    /**
     * Restructure the service response that is being sent back to the user.
     *
     * @param array $data
     *
     * @return array
     */
    protected function formatResponse(array $data): array
    {
        return [
            'id'       => $data['id'],
            'longUrl'  => $data['destination'],
            'shortUrl' => $data['shortUrl'],
        ];
    }
}
