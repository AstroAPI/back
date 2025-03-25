<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api')]
final class UserController extends AbstractController
{
    #[Route('/users', name: 'users.list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/users/{id}', name: 'users.get', methods: ['GET'])]
    public function getUserById(User $user): JsonResponse
    {
        // Un utilisateur peut voir uniquement ses propres données sauf s'il est admin
        if ($this->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['message' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/users', name: 'users.create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createUser(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        // Hasher le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        $location = $urlGenerator->generate('users.get', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->json($user, Response::HTTP_CREATED, ['Location' => $location], ['groups' => 'user:read']);
    }

    #[Route('/users/{id}', name: 'users.update', methods: ['PUT'])]
    public function updateUser(
        User $user,
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse
    {
        // Un utilisateur peut modifier uniquement ses propres données sauf s'il est admin
        if ($this->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['message' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        
        $updatedUser = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        // Mise à jour des champs
        $user->setEmail($updatedUser->getEmail());
        
        // Ne mettre à jour le rôle que si l'utilisateur est admin
        if ($this->isGranted('ROLE_ADMIN')) {
            $user->setRoles($updatedUser->getRoles());
        }
        
        // Si un nouveau mot de passe est fourni, le hasher
        if ($updatedUser->getPassword()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $updatedUser->getPassword());
            $user->setPassword($hashedPassword);
        }
        
        $entityManager->flush();
        
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/users/{id}', name: 'users.delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();
        
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/me', name: 'users.me', methods: ['GET'])]
    public function getCurrentUser(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['message' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }
        
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }
}
