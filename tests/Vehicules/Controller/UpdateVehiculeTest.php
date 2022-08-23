<?php

namespace App\Tests\Vehicules\Controller;

use App\Tests\Vehicules\Service\VehiculeServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateVehiculeTest extends WebTestCase
{
    public function testUpdateVehicule()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/api/vehicule/' . $data->getId() . '/update',
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

    public function testUpdateVehiculeNotFound()
    {
        $client = static::createClient();

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/api/vehicule/122/update',
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

    public function testUpdateVehiculeBadRequest()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);

        $client->jsonRequest(
            'PUT',
            'http://localhost:8080/api/vehicule/' . $data->getId() . '/update',
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
