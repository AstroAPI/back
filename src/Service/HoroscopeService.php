<?php

namespace App\Service;

use App\Repository\HoroscopeTemplateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HoroscopeService
{
    private $weatherService;
    private $templateRepository;
    private $logger;
    private $cache;
    private $defaultTemplates;

    public function __construct(
        OpenWeatherMapService $weatherService, 
        HoroscopeTemplateRepository $templateRepository,
        LoggerInterface $weatherLogger,
        TagAwareCacheInterface $horoscopeCache
    ) {
        $this->weatherService = $weatherService;
        $this->templateRepository = $templateRepository;
        $this->logger = $weatherLogger;
        $this->cache = $horoscopeCache;
        
        // Templates par défaut si aucun n'est trouvé en base
        $this->initializeDefaultTemplates();
    }
    
    private function initializeDefaultTemplates()
    {
        $this->defaultTemplates = [
            'Clear' => [
                'hot' => 'Journée ensoleillée et chaude, parfait pour vos projets de %sign%!',
                'moderate' => 'Temps clair et agréable aujourd\'hui, idéal pour %sign% de se ressourcer.',
                'cold' => 'Ciel dégagé mais frais, %sign% devrait prévoir une veste aujourd\'hui.'
            ],
            'Rain' => [
                'hot' => 'Pluie chaude aujourd\'hui, %sign% pourrait trouver de l\'inspiration.',
                'moderate' => 'Restez au chaud, %sign%, la pluie est prévue!',
                'cold' => 'Pluie froide, %sign% devrait rester au chaud et planifier ses projets.'
            ],
            'Clouds' => [
                'hot' => 'Temps nuageux mais chaud, %sign% aura une journée productive.',
                'moderate' => 'Ciel couvert mais température agréable, %sign% devrait rester positif.',
                'cold' => 'Nuages et froid aujourd\'hui, %sign% devrait prendre soin de soi.'
            ],
            'Snow' => [
                'cold' => 'Neige attendue, %sign% devrait reporter ses déplacements et rester au chaud.'
            ],
            'Thunderstorm' => [
                'hot' => 'Orages et chaleur, journée électrique pour %sign%!',
                'moderate' => 'Attention aux orages, %sign%, restez prudent aujourd\'hui.',
                'cold' => 'Orages et temps froid, %sign% devrait éviter les sorties.'
            ],
            'default' => 'Aujourd\'hui, %sign% devrait faire confiance à son intuition.'
        ];
    }

    public function generateHoroscopeForSign(string $sign, string $city): array
    {
        // Clé de cache unique basée sur le signe et la ville
        $cacheKey = 'horoscope_' . strtolower($sign) . '_' . strtolower($city);
        
        // Utiliser le cache avec une durée de vie d'une heure
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($sign, $city) {
            $item->expiresAfter(3600); // 1 heure
            $item->tag(['horoscope', 'sign_' . strtolower($sign), 'city_' . strtolower($city)]);
            
            $this->logger->info('Génération d\'un nouvel horoscope pour ' . $sign . ' à ' . $city);
            
            // Normaliser le signe (première lettre en majuscule)
            $sign = ucfirst(strtolower($sign));
            
            // Valider le signe
            $validSigns = ['Bélier', 'Taureau', 'Gémeaux', 'Cancer', 'Lion', 'Vierge', 
                           'Balance', 'Scorpion', 'Sagittaire', 'Capricorne', 'Verseau', 'Poissons'];
            
            if (!in_array($sign, $validSigns)) {
                return [
                    'success' => false,
                    'message' => 'Signe astrologique invalide. Valeurs acceptées: ' . implode(', ', $validSigns)
                ];
            }
            
            // Récupérer les données météo
            $weatherData = $this->weatherService->getCurrentWeather($city);
            
            if (!$weatherData['success']) {
                return [
                    'success' => false,
                    'message' => 'Impossible de générer l\'horoscope: ' . ($weatherData['error'] ?? 'Erreur inconnue')
                ];
            }
            
            $temperature = $weatherData['data']['temperature'];
            $condition = $weatherData['data']['condition'];
            
            // Déterminer la catégorie de température
            $tempCategory = $this->getTempCategory($temperature);
            
            // Générer l'horoscope
            $horoscopeText = $this->getHoroscope($sign, $condition, $tempCategory);
            
            return [
                'success' => true,
                'sign' => $sign,
                'city' => $city,
                'weather' => [
                    'temperature' => $temperature,
                    'condition' => $condition,
                    'description' => $weatherData['data']['description'],
                ],
                'horoscope' => $horoscopeText,
                'cached' => false // Au moment de la génération, ce n'est pas encore en cache
            ];
        });
    }
    
    /**
     * Invalide le cache pour un signe et/ou une ville spécifique
     */
    public function invalidateCache(?string $sign = null, ?string $city = null): void
    {
        $tags = ['horoscope'];
        
        if ($sign) {
            $tags[] = 'sign_' . strtolower($sign);
        }
        
        if ($city) {
            $tags[] = 'city_' . strtolower($city);
        }
        
        $this->cache->invalidateTags($tags);
        $this->logger->info('Cache invalidé pour les tags: ' . implode(', ', $tags));
    }
    
    private function getTempCategory(float $temperature): string
    {
        if ($temperature >= 25) {
            return 'hot';
        } elseif ($temperature <= 10) {
            return 'cold';
        } else {
            return 'moderate';
        }
    }
    
    private function getHoroscope(string $sign, string $condition, string $tempCategory): string
    {
        // Essayer de trouver un template spécifique en base de données
        $template = $this->templateRepository->findBySignAndWeather($sign, $condition, $tempCategory);
        
        if ($template) {
            return $template->getTemplate();
        }
        
        // Si aucun template spécifique, chercher un template par défaut en base
        $defaultDbTemplate = $this->templateRepository->findDefaultTemplate($sign);
        
        if ($defaultDbTemplate) {
            return $defaultDbTemplate->getTemplate();
        }
        
        // Utiliser le template par défaut du code
        $templateText = $this->defaultTemplates[$condition][$tempCategory] ?? $this->defaultTemplates['default'];
        
        return str_replace('%sign%', $sign, $templateText);
    }
}