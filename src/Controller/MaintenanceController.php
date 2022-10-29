<?php

namespace App\Controller;

use App\Service\ApiService;
use App\Service\VehiculeService;
use App\Service\MaintenanceService;
use App\Repository\VehiculeRepository;
use App\Repository\MaintenanceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class MaintenanceController extends AbstractController
{
    /**
     * MaintenanceController constructor
     *
     * @param ApiService $apiService
     * @param MaintenanceRepository $maintenanceRepository
     * @param MaintenanceService $maintenanceService
     * @param Security $security
     * @param VehiculeRepository $vehiculeRepository
     */
    public function __construct(
        private readonly ApiService $apiService,
        private readonly MaintenanceRepository $maintenanceRepository,
        private readonly MaintenanceService $maintenanceService,
        private readonly Security $security,
        private readonly VehiculeRepository $vehiculeRepository,
    )
    {
    }

    #[Route('/vehicule/{id}/maintenances', methods: 'GET')]
    public function getMaintenanceCollection(int $id): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $id);

        if (empty($vehicule)) {
            return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $id));
        }

        $maintenances = $vehicule->getMaintenances();

        if (!empty($maintenances)) {
            return $this->maintenanceService->findMaintenances(
                $maintenances,
                'maintenance:read:collection'
            );
        }

        return $this->apiService->respondNotFound(sprintf('Maintenances not found'));
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}', methods: 'GET')]
    public function getMaintenanceItem(int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $vehiculeId);

        if (empty($vehicule)) {
            return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $vehiculeId));
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            return $this->maintenanceService->findMaintenances(
                $maintenance,
                'maintenance:read:item'
            );
        }

        return $this->apiService->respondNotFound(sprintf('Maintenance %s not found', $maintenanceId));
    }

    #[Route('/vehicule/{id}/maintenance/create', methods: 'POST')]
    public function createMaintenance(Request $request, int $id): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $id);

        if (empty($vehicule)) {
            return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $id));
        }

        return $this->maintenanceService->editMaintenance($request, null, $vehicule);
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}/update', methods: 'PUT')]
    public function updateMaintenance(Request $request, int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $vehiculeId);

        if (empty($vehicule)) {
            return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $vehiculeId));
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            return $this->maintenanceService->editMaintenance($request, $maintenance, $vehicule);
        }

        return $this->apiService->respondNotFound(sprintf('Maintenance %s not found', $maintenanceId));
    }

    #[Route('/vehicule/{vehiculeId}/maintenance/{maintenanceId}/delete', methods: 'DELETE')]
    public function deleteMaintenance(int $vehiculeId, int $maintenanceId): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $vehiculeId);

        if (empty($vehicule)) {
            return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $vehiculeId));
        }

        $maintenance = $this->maintenanceRepository->findMaintenanceFromVehicule($vehicule, $maintenanceId);

        if ($maintenance) {
            return $this->maintenanceService->deleteMaintenance($maintenance);
        }

        return $this->apiService->respondNotFound(sprintf('Maintenance %s not found', $maintenanceId));
    }
}
