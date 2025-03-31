<?php

namespace App\$this->cache = $horoscopeCache;
        
        // Templates par défaut si aucun n'est trouvé enService;

use App\Repository\Horoscope base
        $this->initializeDefaultTemplates();
    }
    
    private function initializeDefaultTemplates()
    {
        $thisTemplateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HoroscopeService
{
    private $weatherService;
    private $templateRepository;
    private->defaultTemplates = [
            'Clear' => [
                'hot' => 'Journée ensoleillée et chaude, parfait pour vos projets de %sign%! $logger;
    private $cache;
    private $defaultTemplates;

    public function __construct(
        OpenWeatherMapService $weatherService, 
        HoroscopeTemplateRepository $',
                'moderate' => 'templateRepository,
        LoggerInterface $weatherLogger,
        TagAwareCacheInterface $horoscopeCacheTemps clair et agréable aujourd\'hui, idéal pour %
    ) {
        $this->weatherService =sign% de se ressourcer.',
                'cold' => 'Ciel dégagé mais frais, %sign% devrait prévoir une veste aujourd\'hui.'
            ], $weatherService;
        $this->templateRepository = $templateRepository;
        $this->logger = $
            'Rain' => [
                'hot'weatherLogger;
        $this->cache = $horoscopeCache;
        
        // => 'Pluie chaude aujourd\'hui, %sign% pourrait trouver de l Templates par défaut si aucun n'est tro\'inspiration.',
                'moderateuvé en base
        $this->initializeDefaultTemplates();
    }
    
    /**
     * Simple' => 'Restez au chaud, %sign%, la pluie est prévue!',
                ' method to display daily horoscope (fromcold' => 'Pluie froide, %sign% dev philippe_back)
     */
    public functionrait rester au chaud et plan displayDailyHoroscope()ifier ses projets.'
            
    {
        // Code pour obtenir et],
            'Clouds' => [
                'hot' afficher l'horoscope du jour
         => 'Temps nuageux mais chaud, %sign% aecho "L'horoscope du jour : Vous allez avoir uneura une journée productive.',
                'moderate' => ' journée incroyable !";
    }
    
    private function initializeDefaultTemplates()Ciel couvert mais température agréable, %sign% devrait
    {
        $this->defaultTemplates rester positif.',
                'cold' => 'Nuages et froid aujourd\'hui, %sign% devrait pr = [
            'Clear' => [
                'hot' => 'Journendre soin de soi.'
            ],
            'Snow' => [
                'cold' => 'ée ensoleillée et chaude, parfait pour vos projets de %Neige attendue, %sign% devrait reporter ses déplacements et rester au chausign%!',
                'moderate' => 'Temps clair et agd.'
            ],
            'Thunderstorm' => [
                'hot' =>réable aujourd\'hui, idéal pour % 'Orages et chaleur, journée électrique pour %sign%!',
                'moderate' => sign% de se ressourcer.',
                'col'Attention aux orages, %sign%,d' => 'Ciel dégagé mais frais, %sign% devrait pré restez prudent aujourd\'hui.',voir une veste aujourd\'hui.'
            ],
                'cold' => 'Orages et temps
            'Rain' => [
                'hot' froid, %sign% devrait éviter les sorties.'
            ],
            'default => 'Pluie chaude aujourd\'hui, %sign% pour' => 'Aujourd\'hui, %sign% devrait faire confiance à son intuition.'
        ];
    }

    rait trouver de l\'inspirationpublic function generateHoroscopeFor.',
                'moderate' => 'Sign(string $sign, string $cityRestez au chaud, %): array
    {
        // Clsign%, la pluie est prévue!é de cache unique basée sur le signe et la ville
        $c',
                'cold' => 'PacheKey = 'horoscope_' . stluie froide, %sign% devrait rrtolower($sign) . '_' . strtester au chaud et planifier ses projets.'
            olower($city);
        
        // Utiliser le cache avec une durée de vie ],
            'Clouds' => [
                'hot' => 'Temps nuageux maisd'une heure
        return $this->cache->get($c chaud, %sign% aura une journacheKey, function (ItemInterface $item) use ($sign, $city) {
            $item->expiresAfter(3600); // 1 heureée productive.',
                'moderate' => 'Ciel couvert mais température agréable, %sign% dev
            $item->tag(['horoscope', 'sign_' . strtolower($sign), 'city_' . strtolower($city)]);rait rester positif.',
                'cold' => 'Nuages et froid aujourd\'hui, %sign% devrait prendre soin de s
            
            $this->logger->info('Génération d\'un nouvel horoscopeoi.'
            ],
            'Snow' => [ pour ' . $sign . ' à ' .
                'cold' => 'Neige attendue, %sign% devrait reporter ses dé $city);
            
            // Normaliser le signe (première lettre en majuscule)
            $sign = ucfirst(strtolowerplacements et rester au chaud.'
            ],($sign));
            
            // Valider le
            'Thunderstorm' => signe
            $validSigns = ['B [
                'hot' => 'Orages etélier', 'Taureau', 'Gémeaux', 'Cancer', 'Lion chaleur, journée électrique pour %sign%!', 'Vierge', ',
                'moderate' => 'Attention aux or
                           'Balance', 'Scorpion', 'Sagittaire', ages, %sign%, restez prudent aujourd\'hui.','Capricorne', 'Verseau', 'Poissons'];
            
            if (!in_array($sign, $validSigns)) {
                return [
                    'success' =>
                'cold' => 'Orages et temps froid, %sign% devrait éviter les sor false,
                    'message' => 'ties.'
            ],
            'defaultSigne astrologique invalide. Valeurs acceptées: ' . implode(', ', $validSigns)
                ];
            }
            
            // Récupérer les données météo
            $weatherData = $' => 'Aujourd\'hui, %sign% devrait faire confiance à son intuition.'this->weatherService->getCurrentWeather($city);
        ];
    }

    public
            
            if (!$weatherData function generateHoroscopeForSign(['success']) {
                return [
                    'string $sign, string $city): array
    {
        // Clésuccess' => false,
                    'message' => de cache unique basée sur le s 'Impossible de générer l\'horoscopeigne et la ville
        $cacheKey = : ' . ($weatherData['error'] ?? 'Erreur in'horoscope_' . strtolower($connue')
                ];
            sign) . '_' . strtolower($city}
            
            $temperature =);
        
        // Utiliser le cache avec une durée de vie d'une heure
        return $weatherData['data']['temperature'];
            $condition = $weatherData['data']['condition'];
             $this->cache->get($cacheKey
            // Déterminer la catégorie de température
            $tempCategory = $this->getTempCategory, function (ItemInterface $item) use ($sign($temperature);
            
            // Générer l'horoscope
            $horoscopeText, $city) {
            $item->expiresAfter(3600); = $this->getHoroscope($sign, $condition, $tempCategory);
            
            return // 1 heure
            $item->tag(['horoscope', 'sign_ [
                'success' => true,
                'sign' => $sign,
                'city' =>' . strtolower($sign), 'city_' . $city,
                'weather' => [
                 strtolower($city)]);
                'temperature' => $temperature,
                    '
            $this->logger->info('Géncondition' => $condition,
                    'description'ération d\'un nouvel horoscope => $weatherData['data']['description'],
                ],
                'horoscope' => $horos pour ' . $sign . ' à ' . $city);
            
            // Normaliser le signecopeText,
                'cached' => false // Au moment de la génération, ce n'est pas encore en cache
            ];
        }); (première lettre en majuscule)
            $sign =
    }
    
    /**
     * ucfirst(strtolower($sign)); Invalide le cache pour un signe et/ou une
            
            // Valider le signe
             ville spécifique
     */
    public function$validSigns = ['Bélier',  invalidateCache(?string $sign ='Taureau', 'Gémeaux',  null, ?string $city = null): void
    'Cancer', 'Lion', 'Vier{
        $tags = ['horoscope'];
        
        if ($sign) {
            $tagsge', 
                           'Balance', '[] = 'sign_' . strtolower($sign);
        }
        
        if ($city) {
            $tags[]Scorpion', 'Sagittaire', 'Capric = 'city_' . strtolower($orne', 'Verseau', 'Poissons'];
            
            ifcity);
        }
        
        $this->cache->invalidateTags($tags (!in_array($sign, $validSigns)) {
                return [
                    'success' =>);
        $this->logger-> false,
                    'message' => 'Signe astrologique invalide. Valeurs acceptées: ' . implode(', ', $validSigns)
                ];
            }
            
            // Récupérer les données météo
            $weatherData =info('Cache invalidé pour les tags: ' . implode(', ', $tags));
    }
     $this->weatherService->getCurrentWeather($city);
            
            if (!$weatherData['success']) {
                return [
                    'success' => false,
                    
    private function getTempCategory(float $temperature): string
    {
        if ($temperature >= 25'message' => 'Impossible de générer l) {
            return 'hot';
        }\'horoscope: ' . ($weatherData['error elseif ($temperature <= 10'] ?? 'Erreur inconnue')
                ];
            }
            
            $temperature =) {
            return 'cold';
        } else {
            return 'moderate';
        }
    }
    
    private function $weatherData['data']['temperature'];
            $condition getHoroscope(string $ = $weatherData['data']['condition'];
            
            // Déterminer la catégorie de température
            $tempCategory = $this->getTempCategorysign, string $condition, string $tempCategory): string
    {
        // Essayer de trouver un template spécifique en base de données
        $template =($temperature);
            
            // Générer l'horoscope
            $horoscopeText $this->templateRepository->findBy = $this->getHoroscope($sign, $SignAndWeather($sign, $condition, $tempcondition, $tempCategory);
            
            return [
                'success' => true,Category);
        
        if ($template) {
                'sign' => $sign,
                '
            return $template->getTemplate();
        }
        
        // Si aucun template spécifique, chercher un template parcity' => $city,
                'weather' => défaut en base
        $defaultDbTemplate = $this->templateRepository->findDefaultTemplate($sign);
        
         [
                    'temperature' => $temperature,
                    'condition' => $condition,
                    if ($defaultDbTemplate) {
            return $defaultDbTemplate->getTemplate();
        }
        'description' => $weatherData['data']['description'],
                ],
                'horoscope' =>
        // Utiliser le template par défaut du code $horoscopeText,
                'cache
        $templateText = $this->defaultTemplatesd' => false // Au moment de la génération, ce n'est pas encore en cache
            ];
        });
    }
    
    /**[$condition][$tempCategory] ?? $this->defaultTempl
     * Invalide le cache pour un signe et/ou une ville spécifique
     */
    public function invalidateCache(?string $ates['default'];
        
        return str_replacesign = null, ?string $city = null): void
    {
        $tags = ['horoscope'];
        
        if ($sign) {
            ('%sign%', $sign, $$tags[] = 'sign_' . strtolower($sign);
        }templateText);
    }
    
    public function displayDailyHoroscope()
    {
        // Pour maint
        
        if ($city) {
            $tags[] = 'city_' . strtolower($city);enir la compatibilité avec le code qui utilisait la
        }
        
        $this->cache->invalidateTags($tags);
        $this-> version précédente
        echo "L'horoscope du jour : Vous allez avoir une journée incroyable !logger->info('Cache invalidé pour les tags: ' . implode(', ', $tags";
    }
}