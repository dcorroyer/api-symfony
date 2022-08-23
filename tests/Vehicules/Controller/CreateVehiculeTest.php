<?php

namespace App\Tests\Vehicules\Controller;

use App\Tests\Vehicules\Service\VehiculeServiceTest;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function json_decode;

class CreateVehiculeTest extends WebTestCase
{
    public function testCreateVehicule()
    {
        $faker  = Factory::create('en-EN');
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);

        $client->jsonRequest(
            'POST',
            'http://localhost:8080/api/vehicule/create',
            [
                'type'           => 'motorcycle',
                'identification' => $faker->creditCardNumber(),
                'brand'          => 'Suzuki',
                'reference'      => 'GSF 650',
                'modelyear'      => 2008
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $vehiculeService->deleteVehicule($client, $content);
    }

    public function testCreateVehiculeBadRequest()
    {
        $faker = Factory::create('en-EN');
        $client = static::createClient();

        $client->jsonRequest(
            'POST',
            'http://localhost:8080/api/vehicule/create',
            [
                'type'           => 'bad type',
                'identification' => $faker->creditCardNumber(),
                'brand'          => 'Suzuki',
                'reference'      => 'GSF 650',
                'modelyear'      => 2008
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
