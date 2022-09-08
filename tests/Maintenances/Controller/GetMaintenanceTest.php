<?php

namespace App\Tests\Maintenances\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetMaintenanceTest extends WebTestCase
{
    public function testGetMaintenanceItem()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'GET',
            '/api/vehicule/1/maintenance/1'
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }

    public function testGetMaintenanceItemNotFound()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'GET',
            '/api/vehicule/1/maintenance/122'
        );

        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
