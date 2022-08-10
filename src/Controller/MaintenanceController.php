<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Repository\MaintenanceRepository;
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

class MaintenanceController extends AbstractController
{
    /**
     * @var EntityManagerInterface $manager
     */
    protected EntityManagerInterface $manager;

    /**
     * @var MaintenanceRepository $maintenanceRepository
     */
    protected MaintenanceRepository $maintenanceRepository;

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
     * MaintenanceController constructor
     *
     * @param EntityManagerInterface $manager
     * @param MaintenanceRepository  $maintenanceRepository
     * @param SerializerInterface    $serializer
     * @param ValidatorInterface     $validator
     * @param VehiculeRepository     $vehiculeRepository
     */
    public function __construct(
        EntityManagerInterface $manager,
        MaintenanceRepository  $maintenanceRepository,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator,
        VehiculeRepository     $vehiculeRepository
    )
    {
        $this->maintenanceRepository = $maintenanceRepository;
        $this->manager               = $manager;
        $this->serializer            = $serializer;
        $this->validator             = $validator;
        $this->vehiculeRepository    = $vehiculeRepository;
    }

    #[Route('/vehicule/{id}/maintenances', name: 'maintenance_read_collection', methods: 'GET')]
    public function getMaintenanceCollection(int $id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if (empty($vehicule)) {
            return new JsonResponse([
                'error' => "Cannot find any Vehicule"
            ], Response::HTTP_NOT_FOUND);
        }

        $maintenances = $vehicule->getMaintenances();

        if ($maintenances) {
            return new JsonResponse(
                $this->serializer->serialize($maintenances, 'json', ['groups' => 'maintenance:read:collection']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot find any Maintenance"
        ], Response::HTTP_NOT_FOUND);
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}', name: 'maintenance_read_item', methods: 'GET')]
    public function getMaintenanceItem(int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($vehiculeId);

        if (empty($vehicule)) {
            return new JsonResponse([
                'error' => "Cannot find Vehicule $vehiculeId"
            ], Response::HTTP_NOT_FOUND);
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            return new JsonResponse(
                $this->serializer->serialize($maintenance, 'json', ['groups' => 'maintenance:read:item']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot find the Maintenance $maintenanceId, Maintenance not found"
        ], Response::HTTP_NOT_FOUND);
    }

    #[Route('/vehicule/{id}/maintenance/create', name: 'maintenance_create_item', methods: 'POST')]
    public function createMaintenance(Request $request, int $id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if (empty($vehicule)) {
            return new JsonResponse([
                'error' => "Cannot find Vehicule $id"
            ], Response::HTTP_NOT_FOUND);
        }

        $maintenance = $this->serializer->deserialize($request->getContent(), Maintenance::class, 'json');
        $errors = $this->validator->validate($maintenance);

        if (count($errors) > 0) {
            return new JsonResponse([
                'error' => (string)$errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $maintenance->setVehicule($vehicule);

        $this->manager->persist($maintenance);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($maintenance, 'json', ['groups' => 'maintenance:write:item']),
            Response::HTTP_CREATED,
            ['Content-type' => 'application/json'],
            true,
        );
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}/update', name: 'maintenance_update_item', methods: 'PUT')]
    public function updateMaintenance(Request $request, int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($vehiculeId);

        if (empty($vehicule)) {
            return new JsonResponse([
                'error' => "Cannot find Vehicule $vehiculeId"
            ], Response::HTTP_NOT_FOUND);
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            $updatedMaintenance = $this->serializer->deserialize(
                $request->getContent(),
                Maintenance::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $maintenance]
            );

            $errors = $this->validator->validate($updatedMaintenance);

            if (count($errors) > 0) {
                return new JsonResponse([
                    'error' => (string)$errors
                ], Response::HTTP_BAD_REQUEST);
            }

            $updatedMaintenance->setUpdatedAt(new DateTime());
            $this->manager->flush();

            return new JsonResponse(
                $this->serializer->serialize($maintenance, 'json', ['groups' => 'maintenance:write:item']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot update the Maintenance $maintenanceId, Maintenance not found"
        ], Response::HTTP_NOT_FOUND);
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}/delete', name: 'maintenance_delete_item', methods: 'DELETE')]
    public function deleteMaintenance(int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($vehiculeId);

        if (empty($vehicule)) {
            return new JsonResponse([
                'error' => "Cannot find Vehicule $vehiculeId"
            ], Response::HTTP_NOT_FOUND);
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            $this->manager->remove($maintenance);
            $this->manager->flush();

            return new JsonResponse(
                $this->serializer->serialize($maintenance, 'json', ['groups' => 'maintenance:write:item']),
                Response::HTTP_OK,
                ['Content-type' => 'application/json'],
                true,
            );
        }

        return new JsonResponse([
            'error' => "Cannot delete the Maintenance $maintenanceId, Maintenance not found"
        ], Response::HTTP_NOT_FOUND);
    }
}
