<?php

namespace App\Tests\Vehicules\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListVehiculeTest extends WebTestCase
{
    public function testGetVehiculeCollection()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $client->loginUser($user);

        $client->jsonRequest(
            'GET',
            '/api/vehicules',
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
