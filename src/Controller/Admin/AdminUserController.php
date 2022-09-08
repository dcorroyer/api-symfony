<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
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

    #[Route('/users', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN', message: 'You don\'t have access')]
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
