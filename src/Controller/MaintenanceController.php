<?php

namespace App\Controller;

use App\Repository\MaintenanceRepository;
use App\Repository\VehiculeRepository;
use App\Service\MaintenanceService;
use App\Service\VehiculeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/api', name: 'api_')]
class MaintenanceController extends AbstractController
{
    /**
     * MaintenanceController constructor
     *
     * @param MaintenanceRepository $maintenanceRepository
     * @param MaintenanceService $maintenanceService
     * @param Security $security
     * @param VehiculeRepository $vehiculeRepository
     * @param VehiculeService $vehiculeService
     */
    public function __construct(
        private readonly MaintenanceRepository  $maintenanceRepository,
        private readonly MaintenanceService     $maintenanceService,
        private readonly Security               $security,
        private readonly VehiculeRepository     $vehiculeRepository,
        private readonly VehiculeService        $vehiculeService,
    )
    {
    }

    #[Route('/vehicule/{id}/maintenances', name: 'maintenance_read_collection', methods: 'GET')]
    public function getMaintenanceCollection(int $id): JsonResponse
    {
        $user = $this->security->getUser();

        if (empty($user)) {
            return new JsonResponse([
                'error' => "Cannot find User"
            ], Response::HTTP_NOT_FOUND);
        }

        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $id);

        if (empty($vehicule)) {
            return $this->vehiculeService->notFoundVehicules();
        }

        $maintenances = $vehicule->getMaintenances();

        if ($maintenances) {
            return $this->maintenanceService->findMaintenances(
                $maintenances,
                'maintenance:read:collection'
            );
        }

        return $this->maintenanceService->notFoundMaintenances();
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}', name: 'maintenance_read_item', methods: 'GET')]
    public function getMaintenanceItem(int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $user = $this->security->getUser();

        if (empty($user)) {
            return new JsonResponse([
                'error' => "Cannot find User"
            ], Response::HTTP_NOT_FOUND);
        }

        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $vehiculeId);

        if (empty($vehicule)) {
            return $this->vehiculeService->notFoundVehicules();
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            return $this->maintenanceService->findMaintenances(
                $maintenance,
                'maintenance:read:item'
            );
        }

        return $this->maintenanceService->notFoundMaintenances();
    }

    #[Route('/vehicule/{id}/maintenance/create', name: 'maintenance_create_item', methods: 'POST')]
    public function createMaintenance(Request $request, int $id): JsonResponse
    {
        $user = $this->security->getUser();

        if (empty($user)) {
            return new JsonResponse([
                'error' => "Cannot find User"
            ], Response::HTTP_NOT_FOUND);
        }

        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $id);

        if (empty($vehicule)) {
            return $this->vehiculeService->notFoundVehicules();
        }

        return $this->maintenanceService->editMaintenance($request, null, $vehicule);
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}/update', name: 'maintenance_update_item', methods: 'PUT')]
    public function updateMaintenance(Request $request, int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $user = $this->security->getUser();

        if (empty($user)) {
            return new JsonResponse([
                'error' => "Cannot find User"
            ], Response::HTTP_NOT_FOUND);
        }

        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $vehiculeId);

        if (empty($vehicule)) {
            return $this->vehiculeService->notFoundVehicules();
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            return $this->maintenanceService->editMaintenance($request, $maintenance, $vehicule);
        }

        return $this->maintenanceService->notFoundMaintenances();
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}/delete', name: 'maintenance_delete_item', methods: 'DELETE')]
    public function deleteMaintenance(int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $user = $this->security->getUser();

        if (empty($user)) {
            return new JsonResponse([
                'error' => "Cannot find User"
            ], Response::HTTP_NOT_FOUND);
        }

        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $vehiculeId);

        if (empty($vehicule)) {
            return $this->vehiculeService->notFoundVehicules();
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            return $this->maintenanceService->deleteMaintenance($maintenance);
        }

        return $this->maintenanceService->notFoundMaintenances();
    }
}
