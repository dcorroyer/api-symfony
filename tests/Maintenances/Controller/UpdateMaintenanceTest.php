<?php

namespace App\Tests\Maintenances\Controller;

use App\Repository\UserRepository;
use App\Tests\Maintenances\Service\MaintenanceServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateMaintenanceTest extends WebTestCase
{
    public function testUpdateMaintenance()
    {
        $client = static::createClient();
        $maintenanceService = $client->getContainer()->get(MaintenanceServiceTest::class);
        $data = $maintenanceService->createMaintenance($client);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'PUT',
            '/api/vehicule/1/maintenance/' . $data->getId() . '/update',
            [
                'type'  => 'repair',
                'amount' => 199.99
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $maintenanceService->deleteMaintenance($client, $data->getId());
    }

    public function testUpdateMaintenanceNotFound()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'PUT',
            '/api/vehicule/1/maintenance/122/update',
            [
                'type'  => 'repair'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    public function testUpdateMaintenanceBadRequest()
    {
        $client = static::createClient();
        $maintenanceService = $client->getContainer()->get(MaintenanceServiceTest::class);
        $data = $maintenanceService->createMaintenance($client);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'PUT',
            '/api/vehicule/1/maintenance/' . $data->getId() . '/update',
            [
                'type' => 'bad type'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $maintenanceService->deleteMaintenance($client, $data->getId());
    }
}
