<?php

namespace App\Tests\Vehicules\Controller;

use App\Repository\UserRepository;
use App\Tests\Vehicules\Service\VehiculeServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteVehiculeTest extends WebTestCase
{
    public function testDeleteVehicule()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'DELETE',
            '/api/vehicule/' . $data->getId() . '/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    public function testDeleteVehiculeNotFound()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);
        
        $client->jsonRequest(
            'DELETE',
            '/api/vehicule/122/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
