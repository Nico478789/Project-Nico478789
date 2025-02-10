<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

#[Route('api/reviews', name: 'app_api_reviews_')]
/** @OA\Post(
 *     path="/api/reviews",
 *     summary="Ajout d'un avis",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Informations à ajouter",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="author", type="string", example="nom d'utilisateur"),
 *             @OA\Property(property="grade", type="int", example="note"),
 *             @OA\Property(property="comment", type="string", example="commentaires"),
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="user_id_id", type="int", example="1")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Avis ajouté avec succès")
 *  )
 */
class ReviewController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator, private SerializerInterface $serializer, private EntityManagerInterface $manager, private UserRepository $user, private ReviewRepository $repository) {}

    #[Route(name: 'new', methods: 'POST')]
    public function new(Request $request): Response
    {

        $review = $this->serializer->deserialize($request->getContent(), Review::class, 'json');
        $user = $this->getUser();
        $review->setAuthor($user);
        $review->setStatus("created");

        $this->manager->persist($review);
        // Actually executes the queries (i.e. the INSERT query)
        $this->manager->flush();

        $responseData = $this->serializer->serialize($review, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_reviews_show',
            ['id' => $review->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    /** @OA\Get(
     *     path="/api/reviews/{id}",
     *     summary="Afficher un avis par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'avis à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Avis trouvé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="author", type="string", example="nom d'utilisateur"),
     *             @OA\Property(property="grade", type="int", example="note"),
     *             @OA\Property(property="comment", type="string", example="commentaires"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="user_id_id", type="int", example="1")
     *             )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Avis non trouvé"
     *     )
     * )
     */
    public function show(int $id): Response
    {
        $review = $this->repository->findOneBy(['id' => $id]);

        if ($review) {
            $responseData = $this->serializer->serialize($review, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    /** @OA\Put(
     *     path="/api/reviews/{id}",
     *     summary="Modifier un avis par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'avis' à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de l'avis à modifier",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="grade", type="int", example="note"),
     *             @OA\Property(property="comment", type="string", example="commentaires"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Avis modifié avec succès")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Avis non modifié"
     *     )
     * )
     */
    public function edit(int $id, Request $request): Response
    {
        $review = $this->repository->findOneBy(['id' => $id]);

        if ($review) {
            $review = $this->serializer->deserialize($request->getContent(), Review::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $review]);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    /** @OA\Delete(
     *     path="/api/reviews/{id}",
     *     summary="Supprimer un avis par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'avis à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Avis supprimé avec succès")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Avis non supprimé"
     *     )
     * )
     */
    public function delete(int $id): Response
    {
        $review = $this->repository->findOneBy(['id' => $id]);
        if ($review) {
            $this->manager->remove($review);
            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
