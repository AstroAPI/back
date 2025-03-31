<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InfoJourRepository;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @OA\Tag(name="Informations du jour")
 */
#[Route('/api/infojour')]
class InfoJourController extends AbstractController
{
    /**
     * Récupère les informations du jour.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne les informations du jour",
     *     @OA\JsonContent(type="array", @OA\Items(
     *        type="object",
     *        @OA\Property(property="id", type="integer", example=1),
     *        @OA\Property(property="title", type="string", example="Événement astronomique"),
     *        @OA\Property(property="content", type="string", example="Éclipse lunaire visible ce soir"),
     *        @OA\Property(property="date", type="string", format="date-time", example="2025-03-31T11:33:33Z")
     *     ))
     * )
     * @Security(name="Bearer")
     */
    #[Route('', name: 'get_info_jour', methods: ['GET'])]
    public function getInfoJour(InfoJourRepository $infoJourRepository): JsonResponse
    {
        $infoJour = $infoJourRepository->findAll();
        return $this->json($infoJour);
    }
}