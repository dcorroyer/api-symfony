<?php

namespace App\Tests\Maintenances\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetMaintenanceTest extends WebTestCase
{
    public function testGetMaintenanceItem()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/api/vehicule/1/maintenance/1'
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    public function testGetMaintenanceItemNotFound()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/api/vehicule/1/maintenance/122'
        );

        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
