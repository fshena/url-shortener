<?php declare(strict_types=1);

namespace Tests\ShortenEndpoint\Services;

use \PHPUnit\Framework\TestCase;
use Rebrandly\Service\Link;
use \Rebrandly\Model\Link as LinkModel;
use ShortenEndpoint\Services\RebrandlyServiceAdapter;
use ShortenEndpoint\Services\RedisCacheService;

class RebrandlyServiceAdapterTest extends TestCase
{
    /**
     * @var Link
     */
    private $rebrandlyClientMock;

    /**
     * @var string
     */
    private $url = 'https://news.ycombinator.com/';

    /**
     * Mock response for Bitly API
     *
     * @var array
     */
    private $rebrandlyApiResponse = [
        'id'          => '2GmhKSn',
        'destination' => 'https://news.ycombinator.com/',
        'shortUrl'    => 'http://bit.ly/2GmhKSn',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->rebrandlyClientMock = $this->createPartialMock(Link::class, ['quickcreate']);

        $stub = $this->createMock(LinkModel::class);
        $stub->method('export')->willReturn($this->rebrandlyApiResponse);

        $this->rebrandlyClientMock->method('quickcreate')->willReturn($stub);
    }

    /**
     * Test that the short url service runs even when no
     * cache connection is available.
     */
    public function testShortenUrlWithoutCacheService(): void
    {
        /** @var RebrandlyServiceAdapter $adapter */
        $adapter = new RebrandlyServiceAdapter($this->rebrandlyClientMock);

        $result = $adapter->shortenUrl($this->url);

        foreach (['id', 'longUrl', 'shortUrl'] as $key) {
            $this->assertArrayHasKey($key, $result);
        }

        $this->assertTrue(array_values($result) === array_values($this->rebrandlyApiResponse));
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
        $this->rebrandlyClientMock->expects($this->never())->method('quickcreate');

        /** @var RebrandlyServiceAdapter $adapter */
        $adapter = new RebrandlyServiceAdapter($this->rebrandlyClientMock, $redisMock);

        $result = $adapter->shortenUrl($this->url);

        foreach (['id', 'longUrl', 'shortUrl'] as $key) {
            $this->assertArrayHasKey($key, $result);
        }

        $this->assertTrue(array_values($result) === array_values($this->rebrandlyApiResponse));
    }

}
