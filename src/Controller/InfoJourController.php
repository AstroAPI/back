<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/infojour')]
class InfoJourController extends AbstractController
{
    #[Route('', name: 'get_info_jour', methods: ['GET'])]
    public function getInfoJour(InfoJourRepository $infoJourRepository): JsonResponse
    {
        $infoJour = $infoJourRepository->findAll();
        return $this->json($infoJour);
    }
}
