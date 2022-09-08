<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationController extends AbstractController
{
    /**
     * @param ApiService $apiService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly ApiService             $apiService,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $hasherInterface
     * @return JsonResponse
     */
    #[Route('/register', methods: 'POST')]
    public function register(Request $request, UserPasswordHasherInterface $hasherInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        $password = $request->get('password');
        $email = $request->get('email');

        if (empty($password) || empty($email)){
            return $this->apiService->respondValidationError("Invalid Email or Password");
        }

        $user = new User();
        $user->setPassword($hasherInterface->hashPassword($user, $password))
            ->setEmail($email)
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->apiService->respondWithSuccess(sprintf('User %s successfully created', $user->getEmail()));
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    #[Route('/api/login_check', methods: 'POST')]
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}
