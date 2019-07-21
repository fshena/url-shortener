<?php declare(strict_types=1);

namespace Tests\ShortenEndpoint\Services;

use Hpatoio\Bitly\Client;
use \PHPUnit\Framework\TestCase;
use ShortenEndpoint\Services\BitlyServiceAdapter;
use ShortenEndpoint\Services\RedisCacheService;

class BitlyServiceAdapterTest extends TestCase
{
    /**
     * @var Client
     */
    private $bitlyClientMock;

    /**
     * @var string
     */
    private $url = 'https://news.ycombinator.com/';

    /**
     * Mock response for Bitly API
     * @var array
     */
    private $bitlyApiResponse = [
        'hash'     => '2GmhKSn',
        'long_url' => 'https://news.ycombinator.com/',
        'url'      => 'http://bit.ly/2GmhKSn',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->bitlyClientMock = $this->createPartialMock(Client::class, ['Shorten']);

        $this->bitlyClientMock->method('Shorten')->willReturn($this->bitlyApiResponse);
    }

    /**
     * Test that the short url service runs even when no
     * cache connection is available.
     */
    public function testShortenUrlWithoutCacheService(): void
    {
        /** @var BitlyServiceAdapter $adapter */
        $adapter = new BitlyServiceAdapter($this->bitlyClientMock);

        $result = $adapter->shortenUrl($this->url);

        foreach (['id', 'longUrl', 'shortUrl'] as $key) {
            $this->assertArrayHasKey($key, $result);
        }

        $this->assertTrue(array_values($result) === array_values($this->bitlyApiResponse));
    }

    /**
     * Test that the short url service stores the third party
     * response in the cache if response was not found.
     */
    public function testShortenUrlWithCacheServiceKeyNotFound(): void
    {
        $redisMock = $this->createConfiguredMock(RedisCacheService::class, [
            'checkConnection' => true,
            'get'             => null,
        ]);

        // check that the cache save method was called once
        $redisMock->expects($this->once())->method('set');

        /** @var BitlyServiceAdapter $adapter */
        $adapter = new BitlyServiceAdapter($this->bitlyClientMock, $redisMock);

        $result = $adapter->shortenUrl($this->url);

        foreach (['id', 'longUrl', 'shortUrl'] as $key) {
            $this->assertArrayHasKey($key, $result);
        }

        $this->assertTrue(array_values($result) === array_values($this->bitlyApiResponse));
    }

    /**
     * Test that the short url service retrieves the response data
     * from cache if url has been previously stored.
     */
    public function testShortenUrlWithCacheServiceKeyFound(): void
    {
        $cachedData = json_encode([
            'id'       => '2GmhKSn',
            'longUrl'  => 'https://news.ycombinator.com/',
            'shortUrl' => 'http://bit.ly/2GmhKSn',
        ]);

        $redisMock = $this->createConfiguredMock(RedisCacheService::class, [
            'checkConnection' => true,
            'get'             => $cachedData,
        ]);

        // check that the cache save method was called once
        $redisMock->expects($this->once())->method('get');

        // verify that the "Bitly" API was never called
        $this->bitlyClientMock->expects($this->never())->method('Shorten');

        /** @var BitlyServiceAdapter $adapter */
        $adapter = new BitlyServiceAdapter($this->bitlyClientMock, $redisMock);

        $result = $adapter->shortenUrl($this->url);

        foreach (['id', 'longUrl', 'shortUrl'] as $key) {
            $this->assertArrayHasKey($key, $result);
        }

        $this->assertTrue(array_values($result) === array_values($this->bitlyApiResponse));
    }
}
