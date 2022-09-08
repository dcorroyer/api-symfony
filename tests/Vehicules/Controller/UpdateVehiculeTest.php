<?php

namespace App\Tests\Vehicules\Controller;

use App\Repository\UserRepository;
use App\Tests\Vehicules\Service\VehiculeServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateVehiculeTest extends WebTestCase
{
    public function testUpdateVehicule()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'PUT',
            '/api/vehicule/' . $data->getId() . '/update',
            [
                'type'  => 'motorcycle',
                'brand' => 'Suzuki'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $vehiculeService->deleteVehicule($client, $data->getId());
    }

    public function testUpdateVehiculeNotFound()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'PUT',
            '/api/vehicule/122/update',
            [
                'type'  => 'motorcycle',
                'brand' => 'Suzuki'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    public function testUpdateVehiculeBadRequest()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'PUT',
            '/api/vehicule/' . $data->getId() . '/update',
            [
                'type' => 'bad type'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $vehiculeService->deleteVehicule($client, $data->getId());
    }
}
