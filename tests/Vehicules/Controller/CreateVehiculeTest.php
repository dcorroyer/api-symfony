<?php

namespace App\Tests\Vehicules\Controller;

use App\Repository\UserRepository;
use App\Tests\Vehicules\Service\VehiculeServiceTest;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateVehiculeTest extends WebTestCase
{
    public function testCreateVehicule()
    {
        $faker  = Factory::create('en-EN');
        $client = static::createClient();
        $vehiculeService = $client->getContainer()->get(VehiculeServiceTest::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'POST',
            '/api/vehicule/create',
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'POST',
            '/api/vehicule/create',
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
