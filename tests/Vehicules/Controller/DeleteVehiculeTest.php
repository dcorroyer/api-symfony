<?php

namespace App\Tests\Vehicules\Controller;

use App\Entity\Vehicule;
use App\Tests\Vehicules\Service\VehiculeServiceTest;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteVehiculeTest extends WebTestCase
{
    /** @test */
    public function deleteVehiculeTest()
    {
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $data = $vehiculeService->createVehicule($client);

        $client->jsonRequest(
            'DELETE',
            'http://localhost:8080/vehicule/' . $data->getId() . '/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    /** @test */
    public function deleteVehiculeNotFoundTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'DELETE',
            'http://localhost:8080/vehicule/122/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
