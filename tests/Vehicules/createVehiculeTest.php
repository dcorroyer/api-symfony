<?php

namespace App\Tests\Vehicules;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function json_decode;

class createVehiculeTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function createVehiculeTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'POST',
            'http://localhost:8080/api/vehicule/create',
            [
                'type' => 'motorcycle',
                'identification' => 'dk889ax',
                'brand' => 'Suzuki',
                'reference' => 'GSF 650',
                'modelyear' => 2008
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }

    /** @test */
    public function createVehiculeBadRequestTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'POST',
            'http://localhost:8080/api/vehicule/create',
            [
                'type' => 'bad type',
                'identification' => 'dk889ax',
                'brand' => 'Suzuki',
                'reference' => 'GSF 650',
                'modelyear' => 2008
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }
}
