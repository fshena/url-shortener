<?php declare(strict_types=1);

namespace ShortenEndpoint\Services;

use \Hpatoio\Bitly\Client as Bitly;
use ShortenEndpoint\Interfaces\CacheInterface;
use ShortenEndpoint\Interfaces\ServiceInterface;

class BitlyServiceAdapter extends ServiceAdapterBase implements ServiceInterface
{
    /**
     * @var Link $client
     */
    private $client;

    /**
     * BitlyServiceAdapter constructor.
     *
     * @param Bitly               $client
     * @param CacheInterface|null $cache
     */
    public function __construct(Bitly $client, ?CacheInterface $cache = null)
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

        $response = $this->client->Shorten(['longUrl' => $url]);

        $response = $this->formatResponse($response);

        // if valid response from service store in cache
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
            'id'       => $data['hash'],
            'longUrl'  => $data['long_url'],
            'shortUrl' => $data['url'],
        ];
    }
}
