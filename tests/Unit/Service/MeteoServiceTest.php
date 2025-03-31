<?php

namespace App\Tests\Unit\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Log\LoggerInterface;

class MeteoServiceTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testWeatherApiClientIsAvailable(): void
    {
        $container = static::getContainer();
        
        // Check that the HTTP client service is available
        $httpClient = $container->get(HttpClientInterface::class);
        $this->assertInstanceOf(HttpClientInterface::class, $httpClient);
    }
    
    public function testRedisClientIsConfigured(): void
    {
        // Skip this test if Redis is not configured in test environment
        if (getenv('REDIS_URL') === false) {
            $this->markTestSkipped('Redis is not configured');
        }
        
        // Just verify that Redis URL is in expected format
        $redisUrl = getenv('REDIS_URL');
        $this->assertStringContainsString('redis://', $redisUrl);
    }
}