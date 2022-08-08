<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class VehiculeController extends AbstractController
{
    /**
     * @var EntityManagerInterface $manager
     */
    protected EntityManagerInterface $manager;

    /**
     * @var SerializerInterface $serializer
     */
    protected SerializerInterface $serializer;

    /**
     * @var ValidatorInterface $validator
     */
    protected ValidatorInterface $validator;

    /**
     * @var VehiculeRepository $vehiculeRepository
     */
    protected VehiculeRepository $vehiculeRepository;

    /**
     * VehiculeController constructor.
     *
     * @param EntityManagerInterface $manager
     * @param SerializerInterface    $serializer
     * @param ValidatorInterface     $validator
     * @param VehiculeRepository     $vehiculeRepository
     */
    public function __construct(
        EntityManagerInterface $manager,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator,
        VehiculeRepository     $vehiculeRepository
    )
    {
        $this->manager            = $manager;
        $this->serializer         = $serializer;
        $this->validator          = $validator;
        $this->vehiculeRepository = $vehiculeRepository;
    }

    #[Route('/vehicules', name: 'vehicule_read_collection', methods: 'GET')]
    public function getVehiculeCollection(): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->findAll();

        if ($vehicule) {
            return new JsonResponse(
                $this->serializer->serialize($vehicule, 'json', ['groups' => 'vehicule:read:collection']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot find any Vehicule"
        ], Response::HTTP_NOT_FOUND);
    }

    #[Route('/vehicule/{id}', name: 'vehicule_read_item', methods: 'GET')]
    public function getVehiculeItem($id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if ($vehicule) {
            return new JsonResponse(
                $this->serializer->serialize($vehicule, 'json', ['groups' => 'vehicule:read:item']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot find the Vehicule $id, Vehicule not found"
        ], Response::HTTP_NOT_FOUND);

    }

    #[Route('/vehicule/create', name: 'vehicule_create_item', methods: 'POST')]
    public function createVehicule(Request $request): JsonResponse
    {
        $vehicule = $this->serializer->deserialize($request->getContent(), Vehicule::class, 'json');
        $errors   = $this->validator->validate($vehicule);

        if (count($errors) > 0) {
            return new JsonResponse([
                'error' => (string)$errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->manager->persist($vehicule);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($vehicule, 'json', ['groups' => 'vehicule:write:item']),
            Response::HTTP_CREATED,
            ['Content-type' => 'application/json'],
            true,
        );
    }

    #[Route('/vehicule/{id}/update', name: 'vehicule_update_item', methods: 'PUT')]
    public function updateVehicule(Request $request, $id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if ($vehicule) {
            $updatedVehicule = $this->serializer->deserialize($request->getContent(), Vehicule::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $vehicule]);
            $errors          = $this->validator->validate($vehicule);

            if (count($errors) > 0) {
                return new JsonResponse([
                    'error' => (string)$errors
                ], Response::HTTP_BAD_REQUEST);
            }

            $updatedVehicule->setUpdatedAt(new DateTime());
            $this->manager->flush();

            return new JsonResponse(
                $this->serializer->serialize($vehicule, 'json', ['groups' => 'vehicule:write:item']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot update the Vehicule $id, Vehicule not found"
        ], Response::HTTP_NOT_FOUND);
    }

    #[Route('/vehicule/{id}/delete', name: 'vehicule_delete_item', methods: 'DELETE')]
    public function deleteVehicule($id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if ($vehicule) {
            $this->manager->remove($vehicule);
            $this->manager->flush();

            return new JsonResponse(
                $this->serializer->serialize($vehicule, 'json', ['groups' => 'vehicule:read:item']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot delete the Vehicule $id, Vehicule not found"
        ], Response::HTTP_NOT_FOUND);
    }
}
