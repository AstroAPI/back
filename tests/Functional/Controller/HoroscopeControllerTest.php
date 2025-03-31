<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HoroscopeControllerTest extends WebTestCase
{
    private $token;

    protected function setUp(): void
    {
        // Obtenir un token d'authentification pour les tests
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@example.com',
                'password' => 'password123'
            ])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->token = $responseData['token'] ?? null;
    }

    public function testGetHoroscopeData()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/horoscope?sign=cancer',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetHoroscopeDataWithInvalidSign()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/horoscope?sign=invalidSign',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }
}