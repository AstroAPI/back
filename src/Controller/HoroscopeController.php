<?php
namespace App\Controller;

use App\Service\HoroscopeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;  // Noter cette ligne

class HoroscopeController extends AbstractController
{
    private $horoscopeService;
    
    public function __construct(HoroscopeService $horoscopeService)
    {
        $this->horoscopeService = $horoscopeService;
    }
    
    #[Route('/api/horoscope/list', name: 'get_signs_list')]  // Syntaxe d'attribut PHP 8
    public function getSignsList(): JsonResponse
    {
        $signs = ['Bélier', 'Taureau', 'Gémeaux', 'Cancer', 'Lion', 'Vierge', 
                'Balance', 'Scorpion', 'Sagittaire', 'Capricorne', 'Verseau', 'Poissons'];
        
        return new JsonResponse([
            'success' => true,
            'signs' => $signs
        ]);
    }
    
    #[Route('/api/horoscope/{sign}', name: 'get_horoscope')]
    public function getHoroscope(string $sign, Request $request): JsonResponse
    {
        try {
            $city = $request->query->get('city', 'Paris');
            $result = $this->horoscopeService->generateHoroscopeForSign($sign, $city);
            return new JsonResponse($result);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    }
}