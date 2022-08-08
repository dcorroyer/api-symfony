<?php

namespace App\Tests\Vehicules;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class updateVehiculeTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function updateVehiculeTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/api/vehicule/1/update',
            [
                'type' => 'motorcycle',
                'brand' => 'Suzuki'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }

    /** @test */
    public function updateVehiculeNotFoundTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/api/vehicule/122/update',
            [
                'type' => 'motorcycle',
                'brand' => 'Suzuki'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }

    /** @test */
    public function updateVehiculeBadRequestTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/api/vehicule/1/update',
            [
                'type' => 'bad type'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }
}
