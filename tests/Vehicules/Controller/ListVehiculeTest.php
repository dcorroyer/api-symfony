<?php

namespace App\Tests\Vehicules\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListVehiculeTest extends WebTestCase
{
    /** @test */
    public function getVehiculeCollectionTest()
    {
        $client = static::createClient();
        $client->jsonRequest(
            'GET',
            'http://localhost:8080/vehicules'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
    }
}
