<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use App\Service\VehiculeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VehiculeController extends AbstractController
{
    /**
     * VehiculeController constructor.
     *
     * @param VehiculeRepository $vehiculeRepository
     * @param VehiculeService    $vehiculeService
     */
    public function __construct(
        private readonly VehiculeRepository $vehiculeRepository,
        private readonly VehiculeService    $vehiculeService
    )
    {
    }

    #[Route('/vehicules', name: 'vehicule_read_collection', methods: 'GET')]
    public function getVehiculeCollection(): JsonResponse
    {
        $vehicules = $this->vehiculeRepository->findAll();

        if ($vehicules) {
            return $this->vehiculeService->findVehicules(
                $vehicules,
                'vehicule:read:collection'
            );
        }

        return $this->vehiculeService->notFoundVehicules();
    }

    #[Route('/vehicule/{id}', name: 'vehicule_read_item', methods: 'GET')]
    public function getVehiculeItem(int $id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if ($vehicule) {
            return $this->vehiculeService->findVehicules(
                $vehicule,
                'vehicule:read:item'
            );
        }

        return $this->vehiculeService->notFoundVehicules();
    }

    #[Route('/vehicule/create', name: 'vehicule_create_item', methods: 'POST')]
    public function createVehicule(Request $request): JsonResponse
    {
        return $this->vehiculeService->editVehicule($request, null);
    }

    #[Route('/vehicule/{id}/update', name: 'vehicule_update_item', methods: 'PUT')]
    public function updateVehicule(Request $request, int $id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if ($vehicule) {
            return $this->vehiculeService->editVehicule($request, $vehicule);
        }

        return $this->vehiculeService->notFoundVehicules();
    }

    #[Route('/vehicule/{id}/delete', name: 'vehicule_delete_item', methods: 'DELETE')]
    public function deleteVehicule(int $id): JsonResponse
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if ($vehicule) {
            return $this->vehiculeService->deleteVehicule($vehicule);
        }

        return $this->vehiculeService->notFoundVehicules();
    }
}
