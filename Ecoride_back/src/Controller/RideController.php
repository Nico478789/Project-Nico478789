<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Repository\RideRepository;
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

#[Route('api/rides', name: 'app_api_rides_')]
/** @OA\Post(
 *     path="/api/rides",
 *     summary="Ajout d'un trajet",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Informations à ajouter",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="car_id_id", type="int", example="1"),
 *             @OA\Property(property="departure_time", type="date-time"),
 *             @OA\Property(property="arrival_time", type="date-time"),
 *             @OA\Property(property="departure_city", type="string", example="Paris"),
 *             @OA\Property(property="arrival_city", type="string", example="Marseille"),
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="number_of_seats", type="int", example="3"),
 *             @OA\Property(property="price", type="int")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Trajet ajouté avec succès")
 *  )
 */
class RideController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator, private SerializerInterface $serializer, private EntityManagerInterface $manager, private RideRepository $repository) {}

    #[Route(name: 'new', methods: 'POST')]
    public function new(Request $request): Response
    {

        $ride = $this->serializer->deserialize($request->getContent(), Ride::class, 'json');

        $this->manager->persist($ride);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($ride, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_rides_show',
            ['id' => $ride->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    /** @OA\Get(
     *     path="/api/rides/{id}",
     *     summary="Afficher un trajet par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du trajet à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trajet trouvé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="car_id_id", type="int", example="1"),
     *             @OA\Property(property="departure_time", type="date-time"),
     *             @OA\Property(property="arrival_time", type="date-time"),
     *             @OA\Property(property="departure_city", type="string", example="Paris"),
     *             @OA\Property(property="arrival_city", type="string", example="Marseille"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="number_of_seats", type="int", example="3"),
     *             @OA\Property(property="price", type="int")
     *             )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trajet non trouvé"
     *     )
     * )
     */
    public function show(int $id): Response
    {
        $ride = $this->repository->findOneBy(['id' => $id]);

        if ($ride) {
            $responseData = $this->serializer->serialize($ride, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    /** @OA\Put(
     *     path="/api/rides/{id}",
     *     summary="Modifier un trajet par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du trajet à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du trajet à modifier",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="departure_time", type="date-time"),
     *             @OA\Property(property="arrival_time", type="date-time"),
     *             @OA\Property(property="departure_city", type="string", example="Paris"),
     *             @OA\Property(property="arrival_city", type="string", example="Marseille"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="number_of_seats", type="int", example="3"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Trajet modifié avec succès")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trajet non modifié"
     *     )
     * )
     */
    public function edit(int $id, Request $request): Response
    {
        $ride = $this->repository->findOneBy(['id' => $id]);

        if ($ride) {
            $ride = $this->serializer->deserialize($request->getContent(), Ride::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $ride]);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    /** @OA\Delete(
     *     path="/api/rides/{id}",
     *     summary="Supprimer un trajet par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du trajet à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Trajet supprimé avec succès")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trajet non supprimé"
     *     )
     * )
     */
    public function delete(int $id): Response
    {
        $ride = $this->repository->findOneBy(['id' => $id]);
        if ($ride) {
            $this->manager->remove($ride);
            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
