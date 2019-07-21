<?php declare(strict_types=1);

namespace Tests\ShortenEndpoint\Services;

use \PHPUnit\Framework\TestCase;
use ShortenEndpoint\Services\ServiceFactory;

class ServiceFactoryTest extends TestCase
{
    /**
     * @var ServiceFactory
     */
    private $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->factory = new ServiceFactory();
    }

    public function testGetAvailableServices(): void
    {
        $services = $this->factory->getAvailableServices();

        $this->assertTrue(array_values($services) === ['bitly', 'rebrandly']);
    }

    public function testCreateWithNoType(): void
    {
        $service = $this->factory->create();

        $interfaceName = array_keys(class_implements($service));

        $this->assertTrue('ShortenEndpoint\Interfaces\ServiceInterface' === $interfaceName[0]);
    }

    public function testCreateWithTypeBitly(): void
    {
        $service = $this->factory->create('bitly');

        $interfaceName = array_keys(class_implements($service));

        $this->assertTrue('ShortenEndpoint\Interfaces\ServiceInterface' === $interfaceName[0]);
    }

    public function testCreateWithTypeRebrandly(): void
    {
        $service = $this->factory->create('rebrandly');

        $interfaceName = array_keys(class_implements($service));

        $this->assertTrue('ShortenEndpoint\Interfaces\ServiceInterface' === $interfaceName[0]);
    }

    public function testCreateWithWrongType(): void
    {
        $service = $this->factory->create('nonExistent');

        $interfaceName = array_keys(class_implements($service));

        $this->assertTrue('ShortenEndpoint\Interfaces\ServiceInterface' === $interfaceName[0]);
    }
}
