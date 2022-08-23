<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AdminUserController extends AbstractController
{
    /**
     * UserController constructor.
     *
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly UserRepository      $userRepository
    )
    {
    }

    #[Route('/users', name: 'user_read_collection', methods: 'GET')]
    public function getUserCollection(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($this->userRepository->findAll(), 'json', ['groups' => 'user:read:collection']),
            Response::HTTP_OK,
            ['Content-type' => 'application/json'],
            true,
        );
    }
}
