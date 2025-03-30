<?php
// src/Service/OpenWeatherMapService.php
namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OpenWeatherMapService
{
    private $apiKey;
    private $apiUrl;
    private $cache;
    private $logger;

    public function __construct(
        ParameterBagInterface $params,
        LoggerInterface $logger
    ) {
        $this->apiKey = $params->get('app.openweathermap.api_key');
        $this->apiUrl = $params->get('app.openweathermap.api_url');
        $this->logger = $logger;

        $redis = RedisAdapter::createConnection($params->get('app.redis.url'));
        $this->cache = new RedisAdapter($redis, 'weather_cache', 3600); // Cache d'une heure
    }

    // Le reste du code reste inchangé...
    public function getCurrentWeather(string $city): array
    {
        $cacheKey = 'weather_' . md5($city);

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($city) {
            $item->expiresAfter(3600); // 1 heure de cache
            
            try {
                $client = HttpClient::create();
                $response = $client->request('GET', sprintf(
                    '%s/weather?q=%s&appid=%s&units=metric&lang=fr',
                    $this->apiUrl,
                    urlencode($city),
                    $this->apiKey
                ));

                $data = $response->toArray();
                $this->logger->info('Données météo récupérées pour ' . $city, [
                    'temperature' => $data['main']['temp'] ?? 'N/A',
                    'condition' => $data['weather'][0]['main'] ?? 'N/A'
                ]);
                
                return [
                    'success' => true,
                    'data' => [
                        'temperature' => $data['main']['temp'] ?? null,
                        'condition' => $data['weather'][0]['main'] ?? null,
                        'description' => $data['weather'][0]['description'] ?? null,
                        'icon' => $data['weather'][0]['icon'] ?? null,
                    ]
                ];
            } catch (TransportExceptionInterface $e) {
                $this->logger->error('Erreur lors de la récupération des données météo: ' . $e->getMessage());
                return [
                    'success' => false,
                    'error' => 'Erreur de connexion à l\'API météo: ' . $e->getMessage()
                ];
            } catch (\Exception $e) {
                $this->logger->error('Erreur inattendue: ' . $e->getMessage());
                return [
                    'success' => false,
                    'error' => 'Erreur inattendue: ' . $e->getMessage()
                ];
            }
        });
    }
}