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
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @OA\Tag(name="Utilisateurs")
 */
#[Route('/api')]
final class UserController extends AbstractController
{
    /**
     * Liste tous les utilisateurs (réservé aux administrateurs).
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des utilisateurs",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"user:read"}))
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="Accès refusé - Nécessite des droits administrateur"
     * )
     * @Security(name="Bearer")
     */
    #[Route('/users', name: 'users.list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    /**
     * Récupère un utilisateur par son ID.
     *
     * Un utilisateur peut voir uniquement ses propres données, sauf s'il est administrateur.
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID de l'utilisateur",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Retourne les détails de l'utilisateur",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:read"}))
     * )
     * @OA\Response(
     *     response=403,
     *     description="Accès refusé"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Utilisateur non trouvé"
     * )
     * @Security(name="Bearer")
     */
    #[Route('/users/{id}', name: 'users.get', methods: ['GET'])]
    public function getUserById(User $user): JsonResponse
    {
        // Un utilisateur peut voir uniquement ses propres données sauf s'il est admin
        if ($this->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['message' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    /**
     * Crée un nouvel utilisateur (réservé aux administrateurs).
     *
     * @OA\RequestBody(
     *     description="Données de l'utilisateur à créer",
     *     required=true,
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="email", type="string", example="user@example.com"),
     *        @OA\Property(property="password", type="string", example="password123"),
     *        @OA\Property(property="city", type="string", example="Paris"),
     *        @OA\Property(property="roles", type="array", @OA\Items(type="string", example="ROLE_USER"))
     *     )
     * )
     * @OA\Response(
     *     response=201,
     *     description="Utilisateur créé avec succès",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:read"}))
     * )
     * @OA\Response(
     *     response=400,
     *     description="Données invalides"
     * )
     * @OA\Response(
     *     response=403,
     *     description="Accès refusé - Nécessite des droits administrateur"
     * )
     * @Security(name="Bearer")
     */
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
        $data = json_decode($request->getContent(), true);

        // Vérifier si la ville est fournie
        if (isset($data['city'])) {
            $user->setCity($data['city']);
        }
        
        // Hasher le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        $location = $urlGenerator->generate('users.get', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->json($user, Response::HTTP_CREATED, ['Location' => $location], ['groups' => 'user:read']);
    }

    /**
     * Met à jour un utilisateur existant.
     *
     * Un utilisateur peut modifier uniquement ses propres données, sauf s'il est administrateur.
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID de l'utilisateur à modifier",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\RequestBody(
     *     description="Données de l'utilisateur à modifier",
     *     required=true,
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="email", type="string", example="updated@example.com"),
     *        @OA\Property(property="password", type="string", example="newpassword123"),
     *        @OA\Property(property="city", type="string", example="Lyon"),
     *        @OA\Property(property="roles", type="array", @OA\Items(type="string", example="ROLE_ADMIN"))
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Utilisateur mis à jour avec succès",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:read"}))
     * )
     * @OA\Response(
     *     response=403,
     *     description="Accès refusé"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Utilisateur non trouvé"
     * )
     * @Security(name="Bearer")
     */
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
        
        // Au lieu de désérialiser directement dans un nouvel objet,
        // extrayez simplement les données de la requête
        $data = json_decode($request->getContent(), true);
        
        // Mise à jour des champs
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        
        if (isset($data['city'])) {
            $user->setCity($data['city']);
        }
        
        // Ne mettre à jour le rôle que si l'utilisateur est admin
        if ($this->isGranted('ROLE_ADMIN') && isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }
        
        // Si un nouveau mot de passe est fourni, le hasher
        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }
        
        $entityManager->flush();
        
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    /**
     * Supprime un utilisateur (réservé aux administrateurs).
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID de l'utilisateur à supprimer",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=204,
     *     description="Utilisateur supprimé avec succès"
     * )
     * @OA\Response(
     *     response=403,
     *     description="Accès refusé - Nécessite des droits administrateur"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Utilisateur non trouvé"
     * )
     * @Security(name="Bearer")
     */
    #[Route('/users/{id}', name: 'users.delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();
        
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Récupère les informations de l'utilisateur actuellement connecté.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne les détails de l'utilisateur connecté",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:read"}))
     * )
     * @OA\Response(
     *     response=401,
     *     description="Utilisateur non authentifié"
     * )
     * @Security(name="Bearer")
     */
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