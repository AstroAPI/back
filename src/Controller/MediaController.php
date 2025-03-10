<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\CustomMedia;

final class MediaController extends AbstractController
{
    #[Route('/', name: 'app_media')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MediaController.php',
        ]);
    }

    #[Route('/media', name: 'media.picture', methods: ['POST'])]
    public function createMedia( Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator){
        $media = new CustomMedia();
        $files = $request->files->get('media');
        $media->setMedia($files);
        $media->setPublicPath('/public/docs/medias')
        $entityManager->persist($media);
        $entityManager->flush();

        $jsonMedia = $serializer->serialize($media, 'json');
        $location = $urlGenerator->generate('media.get', ['id' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonMedia, Response::HTTP_CREATED, ['Location' => $location]);
    
    }

    #[Route('/media/{id}', name: 'media.picture', methods: ['GET'])]
    public function getMedia(customMedia $media, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator){
        $location = $media->getPublishPath(); .'/' . $media->getRealPath();
        $location = $urlGenerator->generate(name: 'app_media', parameters: ['id' => $media->getId()], referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $location = $location . str_replace('/public', '', $media->getPublicPath() . '/' . $media->getRealPath());
        return $media ? new JsonResponse($media, Response::HTTP_OK, ['Location' => $location], false) : new JsonResponse(['error' => 'Media not found'], Response::HTTP_NOT_FOUND);
    
    }
}
