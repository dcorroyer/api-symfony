<?php

namespace App\Tests\Maintenances\Controller;

use App\Tests\Maintenances\Service\MaintenanceServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteMaintenanceTest extends WebTestCase
{
    /** @test */
    public function deleteMaintenanceTest()
    {
        $client = static::createClient();
        $maintenanceService = $client->getContainer()->get(MaintenanceServiceTest::class);
        $data = $maintenanceService->createMaintenance($client);

        $client->jsonRequest(
            'DELETE',
            'http://localhost:8080/vehicule/1/maintenance/' . $data->getId() . '/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    /** @test */
    public function deleteMaintenanceNotFoundTest()
    {
        $client = static::createClient();

        $client->jsonRequest(
            'DELETE',
            'http://localhost:8080/vehicule/1/maintenance/122/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
