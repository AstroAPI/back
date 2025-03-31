<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ApiDocController extends AbstractController
{
    private $renderOpenApi;
    private $twig;

    public function __construct(
        #[Autowire(service: 'nelmio_api_doc.render_docs')] $renderOpenApi,
        Environment $twig
    ) {
        $this->renderOpenApi = $renderOpenApi;
        $this->twig = $twig;
    }

    #[Route('/api/custom-doc', name: 'app.custom_swagger_ui', methods: ['GET'])]
    public function swaggerUi(Request $request): Response
    {
        return new Response(
            $this->twig->render('@NelmioApiDoc/SwaggerUi/index.html.twig', [
                'swagger_data' => [
                    'spec' => $this->renderOpenApi->renderFromRequest($request, 'default', '/api/custom-doc.json'),
                ],
            ])
        );
    }

    #[Route('/api/custom-doc.json', name: 'app.custom_swagger', methods: ['GET'])]
    public function swagger(Request $request): Response
    {
        try {
            $spec = $this->renderOpenApi->renderFromRequest($request, 'default', '/api/custom-doc.json');
            
            return new Response(
                $spec,
                200,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            // Ajouter un debug pour voir l'erreur exacte
            return new Response(
                json_encode([
                    'error' => $e->getMessage(), 
                    'trace' => $e->getTraceAsString()
                ]),
                500,
                ['Content-Type' => 'application/json']
            );
        }
    }
}
