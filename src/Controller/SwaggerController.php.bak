<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Controller\DocumentationController;
use Nelmio\ApiDocBundle\Controller\SwaggerUiController;
use Nelmio\ApiDocBundle\ApiDocGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

class SwaggerController extends AbstractController
{
    #[Route('/api/doc', name: 'app.swagger_ui')]
    public function swaggerUi(
        Request $request,
        #[Autowire(service: 'twig')] ?TwigEnvironment $twig = null,
        #[Autowire(service: 'assets.packages')] ?Packages $assetPackages = null,
        #[Autowire(service: 'nelmio_api_doc.generator.default')] ?ApiDocGenerator $apiDocGenerator = null
    ): Response {
        if (null === $twig || null === $assetPackages || null === $apiDocGenerator) {
            return new Response('Swagger UI dependencies not available', Response::HTTP_SERVICE_UNAVAILABLE);
        }
        
        $controller = new SwaggerUiController($twig, $assetPackages, $apiDocGenerator);
        return $controller->__invoke($request);
    }

    #[Route('/api/doc.json', name: 'app.swagger_json')]
    public function swaggerJson(
        Request $request,
        #[Autowire(service: 'nelmio_api_doc.generator.default')] ?ApiDocGenerator $apiDocGenerator = null
    ): Response {
        if (null === $apiDocGenerator) {
            return new Response('API Doc Generator not available', Response::HTTP_SERVICE_UNAVAILABLE);
        }
        
        $controller = new DocumentationController($apiDocGenerator);
        return $controller->__invoke($request);
    }
}