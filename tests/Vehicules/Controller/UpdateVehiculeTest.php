<?php

namespace App\Tests\Vehicules\Controller;

use App\Tests\Vehicules\Service\VehiculeServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateVehiculeTest extends WebTestCase
{
    /** @test */
    public function updateVehiculeTest()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/vehicule/' . $data->getId() . '/update',
            [
                'type'  => 'motorcycle',
                'brand' => 'Suzuki'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $vehiculeService->deleteVehicule($client, $data->getId());
    }

    /** @test */
    public function updateVehiculeNotFoundTest()
    {
        $client = static::createClient();

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/vehicule/122/update',
            [
                'type'  => 'motorcycle',
                'brand' => 'Suzuki'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    /** @test */
    public function updateVehiculeBadRequestTest()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/vehicule/' . $data->getId() . '/update',
            [
                'type' => 'bad type'
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $vehiculeService->deleteVehicule($client, $data->getId());
    }
}
