<?php

namespace App\Tests\Maintenances\Controller;

use App\Tests\Maintenances\Service\MaintenanceServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateMaintenanceTest extends WebTestCase
{
    /** @test */
    public function updateMaintenanceTest()
    {
        $client = static::createClient();
        $maintenanceService = $client->getContainer()->get(MaintenanceServiceTest::class);
        $data = $maintenanceService->createMaintenance($client);

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/vehicule/1/maintenance/' . $data->getId() . '/update',
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

    /** @test */
    public function updateMaintenanceNotFoundTest()
    {
        $client = static::createClient();

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/vehicule/1/maintenance/122/update',
            [
                'type'  => 'repair'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    /** @test */
    public function updateMaintenanceBadRequestTest()
    {
        $client = static::createClient();
        $maintenanceService = $client->getContainer()->get(MaintenanceServiceTest::class);
        $data = $maintenanceService->createMaintenance($client);

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/vehicule/1/maintenance/' . $data->getId() . '/update',
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
