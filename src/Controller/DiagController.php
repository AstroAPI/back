<?php
// src/Controller/DiagController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DiagController extends AbstractController
{
    #[Route('/api/diag', name: 'api_diag')]
    public function index(ParameterBagInterface $params): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'config' => [
                'api_url' => $params->get('app.openweathermap.api_url'),
                'api_key_set' => !empty($params->get('app.openweathermap.api_key')),
                'redis_url' => $params->get('app.redis.url')
            ]
        ]);
    }
}