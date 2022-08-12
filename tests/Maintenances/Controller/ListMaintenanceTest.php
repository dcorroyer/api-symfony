<?php

namespace App\Tests\Maintenances\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListMaintenanceTest extends WebTestCase
{
    /** @test */
    public function getMaintenanceCollectionTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/vehicule/1/maintenances'
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    /** @test */
    public function getMaintenanceCollectionVehiculeNotFoundTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/vehicule/100/maintenances'
        );

        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
