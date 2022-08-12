<?php

namespace App\Tests\Vehicules\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetVehiculeTest extends WebTestCase
{
    /** @test */
    public function getVehiculeItemTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/vehicule/1'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    /** @test */
    public function getVehiculeItemNotFoundTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/vehicule/122'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
