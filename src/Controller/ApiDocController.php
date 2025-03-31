<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Controller\DocumentationController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ApiDocController extends AbstractController
{
    private $documentationController;

    public function __construct(
        #[Autowire(service: 'nelmio_api_doc.controller.swagger_ui')] DocumentationController $documentationController = null
    ) {
        $this->documentationController = $documentationController;
    }

    #[Route('/api/doc', name: 'app.swagger_ui', methods: ['GET'])]
    public function swaggerUi(): Response
    {
        if (!$this->documentationController) {
            return new Response('Swagger UI controller not available', Response::HTTP_SERVICE_UNAVAILABLE);
        }
        
        return $this->documentationController->__invoke($this->container->get('request_stack')->getCurrentRequest());
    }

    #[Route('/api/doc.json', name: 'app.swagger', methods: ['GET'])]
    public function swagger(): Response
    {
        if (!$this->documentationController) {
            return new Response('Swagger controller not available', Response::HTTP_SERVICE_UNAVAILABLE);
        }
        
        return $this->documentationController->__invoke($this->container->get('request_stack')->getCurrentRequest());
    }
}