<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Vehicule;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VehiculeService
{
    /**
     * VehiculeService constructor.
     *
     * @param EntityManagerInterface $manager
     * @param SerializerInterface    $serializer
     * @param ValidatorInterface     $validator
     */
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly SerializerInterface    $serializer,
        private readonly ValidatorInterface     $validator,
    )
    {
    }

    /**
     * @param mixed $vehicules
     * @param string $groups
     * @return JsonResponse
     */
    public function findVehicules(mixed $vehicules, string $groups): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($vehicules, 'json', ['groups' => $groups]),
            Response::HTTP_OK,
            ['Content-type' => 'application/json'],
            true,
        );
    }

    /**
     * @return JsonResponse
     */
    public function notFoundVehicules(): JsonResponse
    {
        return new JsonResponse([
            'error' => "Cannot find Vehicule(s)"
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param User $user
     * @param Vehicule|null $vehicule
     * @return JsonResponse
     */
    public function editVehicule(Request $request, UserInterface $user, ?Vehicule $vehicule): JsonResponse
    {
        if (!$vehicule) {
            $vehicule = new Vehicule();
            $vehicule->setUser($user);
        }

        $vehicule?->setUpdatedAt(new DateTime());

        $vehicule = $this->serializer->deserialize(
            $request->getContent(),
            Vehicule::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $vehicule] ?? []
        );

        $errors = $this->validator->validate($vehicule);

        if (count($errors) > 0) {
            return new JsonResponse([
                'error' => (string)$errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->manager->persist($vehicule);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($vehicule, 'json', ['groups' => 'vehicule:write:item']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json'],
            true,
        );
    }

    /**
     * @param Vehicule $vehicule
     * @return JsonResponse
     */
    public function deleteVehicule(Vehicule $vehicule): JsonResponse
    {
        $this->manager->remove($vehicule);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($vehicule, 'json', ['groups' => 'vehicule:write:item']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json'],
            true,
        );
    }
}
