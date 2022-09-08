<?php

namespace App\Tests\Maintenances\Controller;

use App\Repository\UserRepository;
use App\Tests\Maintenances\Service\MaintenanceServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteMaintenanceTest extends WebTestCase
{
    public function testDeleteMaintenance()
    {
        $client = static::createClient();
        $maintenanceService = $client->getContainer()->get(MaintenanceServiceTest::class);
        $data = $maintenanceService->createMaintenance($client);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'DELETE',
            '/api/vehicule/1/maintenance/' . $data->getId() . '/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    public function testDeleteMaintenanceNotFound()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'DELETE',
            '/api/vehicule/1/maintenance/122/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
