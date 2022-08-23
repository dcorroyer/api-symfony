<?php

namespace App\Tests\Vehicules\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetVehiculeTest extends WebTestCase
{
    public function testGetVehiculeItem()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/api/vehicule/1'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    public function testGetVehiculeItemNotFound()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/api/vehicule/122'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
