<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
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

#[Route('api/car', name: 'app_api_car_')]
class CarController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator, private SerializerInterface $serializer, private EntityManagerInterface $manager, private CarRepository $repository) {}

    #[Route(name: 'new', methods: 'POST')]
    /** @OA\Post(
     *     path="/api/car",
     *     summary="Ajout d'une voiture",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la voiture a ajouter",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="brand_name", type="string", example="Peugeot"),
     *             @OA\Property(property="model_name", type="string", example="205"),
     *             @OA\Property(property="color", type="string", example="rouge"),
     *             @OA\Property(property="first_registration_date", type="date-time"),
     *             @OA\Property(property="electric", type="boolean"),
     *             @OA\Property(property="user_id_id", type="int", example="1"),
     *             @OA\Property(property="registration_number", type="string", example="AA-111-AA")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Voiture ajoutée avec succès")
     *  )
     */
    public function new(Request $request): Response
    {

        $car = $this->serializer->deserialize($request->getContent(), Car::class, 'json');

        // Tell Doctrine you want to (eventually) save the car (no queries yet)
        $this->manager->persist($car);
        // Actually executes the queries (i.e. the INSERT query)
        $this->manager->flush();

        $responseData = $this->serializer->serialize($car, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_car_show',
            ['id' => $car->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    /** @OA\Get(
     *     path="/api/car/{id}",
     *     summary="Afficher une voiture par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la voiture à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voiture trouvée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="brand_name", type="string", example="Peugeot"),
     *             @OA\Property(property="model_name", type="string", example="205"),
     *             @OA\Property(property="color", type="string", example="rouge"),
     *             @OA\Property(property="first_registration_date", type="date-time"),
     *             @OA\Property(property="electric", type="boolean"),
     *             @OA\Property(property="user_id_id", type="int", example="1"),
     *             @OA\Property(property="registration_number", type="string", example="AA-111-AA")
     *             )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voiture non trouvée"
     *     )
     * )
     */
    public function show(int $id): Response
    {
        $car = $this->repository->findOneBy(['id' => $id]);

        if ($car) {
            $responseData = $this->serializer->serialize($car, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    /** @OA\Put(
     *     path="/api/car/{id}",
     *     summary="Modifier une voiture par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la voiture à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la voiture a modifier",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="brand_name", type="string", example="Peugeot"),
     *             @OA\Property(property="model_name", type="string", example="205"),
     *             @OA\Property(property="color", type="string", example="rouge"),
     *             @OA\Property(property="first_registration_date", type="date-time"),
     *             @OA\Property(property="electric", type="boolean"),
     *             @OA\Property(property="registration_number", type="string", example="AA-111-AA")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Voiture modifiée avec succès")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voiture non modifiée"
     *     )
     * )
     */
    public function edit(int $id, Request $request): Response
    {
        $car = $this->repository->findOneBy(['id' => $id]);

        if ($car) {
            $car = $this->serializer->deserialize($request->getContent(), Car::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $car]);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    /** @OA\Delete(
     *     path="/api/car/{id}",
     *     summary="Supprimer une voiture par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la voiture à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Voiture supprimée avec succès")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voiture non supprimée"
     *     )
     * )
     */
    public function delete(int $id): Response
    {
        $car = $this->repository->findOneBy(['id' => $id]);
        if ($car) {
            $this->manager->remove($car);
            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
