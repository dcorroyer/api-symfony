<?php

namespace App\Service;

use App\Entity\Maintenance;
use App\Entity\Vehicule;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MaintenanceService
{
    /**
     * MaintenanceService constructor
     *
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly SerializerInterface    $serializer,
        private readonly ValidatorInterface     $validator
    )
    {
    }

    /**
     * @param mixed $maintenances
     * @param string $groups
     * @return JsonResponse
     */
    public function findMaintenances(mixed $maintenances, string $groups): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($maintenances, 'json', ['groups' => $groups]),
            Response::HTTP_OK,
            ['Content-type' => 'application/json'],
            true,
        );
    }

    /**
     * @param Request $request
     * @param Maintenance|null $maintenance
     * @param Vehicule $vehicule
     * @return JsonResponse
     */
    public function editMaintenance(Request $request, ?Maintenance $maintenance, Vehicule $vehicule): JsonResponse
    {
        if (!$maintenance) {
            $maintenance = new Maintenance();
            $maintenance->setVehicule($vehicule);
        }

        $maintenance?->setUpdatedAt(new DateTime());

        $maintenance = $this->serializer->deserialize(
            $request->getContent(),
            Maintenance::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $maintenance] ?? []
        );

        $errors = $this->validator->validate($maintenance);

        if (count($errors) > 0) {
            return new JsonResponse([
                'error' => (string)$errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->manager->persist($maintenance);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($maintenance, 'json', ['groups' => 'maintenance:write:item']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json'],
            true,
        );
    }

    /**
     * @param Maintenance $maintenance
     * @return JsonResponse
     */
    public function deleteMaintenance(Maintenance $maintenance): JsonResponse
    {
        $this->manager->remove($maintenance);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($maintenance, 'json', ['groups' => 'maintenance:write:item']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json'],
            true,
        );
    }
}

