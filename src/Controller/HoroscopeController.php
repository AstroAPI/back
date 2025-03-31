<?php
namespace App\Controller;

use App\Service\HoroscopeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

class HoroscopeController extends AbstractController
{
    private $horoscopeService;
    
    public function __construct(HoroscopeService $horoscopeService)
    {
        $this->horoscopeService = $horoscopeService;
    }
    
    /**
     * Liste tous les signes astrologiques disponibles.
     *
     * @OA\Tag(name="Horoscope")
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des signes astrologiques",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="success", type="boolean", example=true),
     *        @OA\Property(
     *          property="signs",
     *          type="array",
     *          @OA\Items(type="string", example="Bélier")
     *        )
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/api/horoscope/list', name: 'get_signs_list', methods: ['GET'])]
    public function getSignsList(): JsonResponse
    {
        $signs = ['Bélier', 'Taureau', 'Gémeaux', 'Cancer', 'Lion', 'Vierge', 
                'Balance', 'Scorpion', 'Sagittaire', 'Capricorne', 'Verseau', 'Poissons'];
        
        return new JsonResponse([
            'success' => true,
            'signs' => $signs
        ]);
    }
    
    /**
     * Génère un horoscope personnalisé pour un signe astrologique et une ville.
     *
     * @OA\Tag(name="Horoscope")
     * @OA\Parameter(
     *     name="sign",
     *     in="path",
     *     description="Signe astrologique",
     *     required=true,
     *     @OA\Schema(type="string", example="Bélier")
     * )
     * @OA\Parameter(
     *     name="city",
     *     in="query",
     *     description="Ville pour la météo (par défaut: Paris)",
     *     required=false,
     *     @OA\Schema(type="string", example="Paris")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Retourne l'horoscope personnalisé",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="success", type="boolean", example=true),
     *        @OA\Property(property="sign", type="string", example="Bélier"),
     *        @OA\Property(property="city", type="string", example="Paris"),
     *        @OA\Property(
     *          property="weather",
     *          type="object",
     *          @OA\Property(property="temperature", type="number", example=19.5),
     *          @OA\Property(property="condition", type="string", example="Clear"),
     *          @OA\Property(property="description", type="string", example="ciel dégagé")
     *        ),
     *        @OA\Property(property="horoscope", type="string", example="Journée ensoleillée et chaude, parfait pour vos projets de Bélier!")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Signe astrologique invalide ou ville non trouvée",
     *     @OA\JsonContent(
     *        @OA\Property(property="success", type="boolean", example=false),
     *        @OA\Property(property="message", type="string", example="Signe astrologique invalide ou ville non trouvée")
     *     )
     * )
     * @OA\Response(
     *     response=500,
     *     description="Erreur serveur",
     *     @OA\JsonContent(
     *        @OA\Property(property="success", type="boolean", example=false),
     *        @OA\Property(property="error", type="string", example="Erreur interne du serveur")
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/api/horoscope/{sign}', name: 'get_horoscope', methods: ['GET'])]
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