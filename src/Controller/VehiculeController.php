<?php

namespace App\Controller;

use App\Service\ApiService;
use App\Service\VehiculeService;
use App\Repository\VehiculeRepository;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api')]
class VehiculeController extends AbstractController
{
    /**
     * VehiculeController constructor.
     *
     * @param ApiService $apiService
     * @param Security $security
     * @param VehiculeRepository $vehiculeRepository
     * @param VehiculeService $vehiculeService
     */
    public function __construct(
        private readonly ApiService         $apiService,
        private readonly Security           $security,
        private readonly VehiculeRepository $vehiculeRepository,
        private readonly VehiculeService    $vehiculeService
    )
    {
    }

    #[Route('/vehicules', methods: 'GET')]
    #[OA\Get(description: "list")]
    #[OA\Response(
        response: 200,
        description: "Ok",
        content: []
    )]
    #[OA\Tag(name: "menu")]
    public function getVehiculeCollection(): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicules = $this->vehiculeRepository->findVehiculesFromUser($user);

        if ($vehicules) {
            return $this->vehiculeService->findVehicules(
                $vehicules,
                'vehicule:read:collection'
            );
        }

        return $this->apiService->respondNotFound(sprintf('Vehicules not found'));
    }

    #[Route('/vehicule/{id}', methods: 'GET')]
    public function getVehiculeItem(int $id): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $id);

        if ($vehicule) {
            return $this->vehiculeService->findVehicules(
                $vehicule,
                'vehicule:read:item'
            );
        }

        return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $vehicule));
    }

    #[Route('/vehicule/create', methods: 'POST')]
    public function createVehicule(Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        return $this->vehiculeService->editVehicule($request, $user, null);
    }

    #[Route('/vehicule/{id}/update', methods: 'PUT')]
    public function updateVehicule(Request $request, int $id): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $id);

        if ($vehicule) {
            return $this->vehiculeService->editVehicule($request, $user, $vehicule);
        }

        return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $vehicule));
    }

    #[Route('/vehicule/{id}/delete', methods: 'DELETE')]
    public function deleteVehicule(int $id): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicule = $this->vehiculeRepository->findVehiculeFromUser($user, $id);

        if ($vehicule) {
            return $this->vehiculeService->deleteVehicule($vehicule);
        }

        return $this->apiService->respondNotFound(sprintf('Vehicule %s not found', $vehicule));
    }
}
