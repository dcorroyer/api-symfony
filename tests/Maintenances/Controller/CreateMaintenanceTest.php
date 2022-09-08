<?php

namespace App\Tests\Maintenances\Controller;

use App\Repository\UserRepository;
use App\Tests\Maintenances\Service\MaintenanceServiceTest;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function json_decode;

class CreateMaintenanceTest extends WebTestCase
{
    public function testCreateMaintenance()
    {
        $faker = Factory::create('en-EN');
        $client = static::createClient();
        $maintenanceService = $client->getContainer()->get(MaintenanceServiceTest::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'POST',
            '/api/vehicule/1/maintenance/create',
            [
                'type'        => 'maintenance',
                'date'        => "2022-08-08T20:49:59+00:00",
                'amount'      => $faker->randomFloat(2),
                'description' => $faker->sentence(mt_rand(3, 5))
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);

        $maintenanceService->deleteMaintenance($client, $content);
    }

    public function testCreateMaintenanceBadRequest()
    {
        $faker = Factory::create('en-EN');
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'POST',
            '/api/vehicule/1/maintenance/create',
            [
                'type'        => 'bad type',
                'date'        => "2022-08-08T20:49:59+00:00",
                'amount'      => $faker->randomFloat(2),
                'description' => $faker->sentence(mt_rand(3, 5))
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
