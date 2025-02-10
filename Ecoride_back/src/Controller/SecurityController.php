<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityManagerInterface $manager, private SerializerInterface $serializer) {}

    #[Route('/registration', name: 'registration', methods: 'POST')]
    /** @OA\Post(
     *     path="/api/registration",
     *     summary="Inscription d'un nouvel utilisateur",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de l'utilisateur à inscrire",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", example="adresse@email.com"),
     *             @OA\Property(property="password", type="string", example="Mot de passe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur inscrit avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="string", example="Nom d'utilisateur"),
     *             @OA\Property(property="apiToken", type="string", example="31a023e212f116124a36af14ea0c1c3806eb9378"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="string", example="ROLE_USER"))
     *         )
     *     )
     *  * )
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse(
            [
                'user' => $user->getUsername(),
                'apiToken' => $user->getApiToken(),
                'roles' => $user->getRoles()
            ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    /** @OA\Post(
     *     path="/api/login",
     *     summary="Connecter un utilisateur",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de l’utilisateur pour se connecter",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="username", type="string", example="adresse@email.com"),
     *             @OA\Property(property="password", type="string", example="Mot de passe")
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Connexion réussie",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="string", example="Nom d'utilisateur"),
     *             @OA\Property(property="apiToken", type="string", example="31a023e212f116124a36af14ea0c1c3806eb9378"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="string", example="ROLE_USER"))
     *          )
     *      )
     * *   )
     */
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse(
            [
                'user' => $user->getUserIdentifier(),
                'apiToken' => $user->getApiToken(),
                'roles' => $user->getRoles()
            ]
        );
    }

    #[Route('/account/me', name: 'me', methods: 'GET')]
    /** @OA\Get(
     *     path="/api/account/me",
     *     summary="Afficher les informations utilisateur",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur trouvé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user", type="string", example="Nom d'utilisateur"),
     *             @OA\Property(property="credits", type="int"),
     *             @OA\Property(property="phone_number", type="string", example="0123456789"),
     *             @OA\Property(property="driver", type="boolean"),
     *             @OA\Property(property="image_name", type="string"),
     *             @OA\Property(property="created_at", type="date-time"),
     *             @OA\Property(property="updated_at", type="date-time")
     *             )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        $responseData = $this->serializer->serialize($user, 'json');

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/account/edit', name: 'edit', methods: 'PUT')]
    /** @OA\Put(
     *     path="/api/account/edit",
     *     summary="Modifier un avis par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de l'utilisateur à modifier",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credits", type="int"),
     *             @OA\Property(property="phone_number", type="string", example="0123456789"),
     *             @OA\Property(property="driver", type="boolean"),
     *             @OA\Property(property="image_name", type="string"),
     *             @OA\Property(property="updated_at", type="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Utilisateur modifié avec succès")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non modifié"
     *     )
     * )
     */
    public function edit(Request $request): JsonResponse
    {
        $user = $this->serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->getUser()],
        );
        //$user->setUpdatedAt(new DateTimeImmutable());

        if (isset($request->toArray()['password'])) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        }

        $this->manager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
