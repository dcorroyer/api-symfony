<?php

namespace App\Tests\Vehicules;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class listVehiculeTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function getVehiculeCollectionTest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            'http://localhost:8080/api/vehicules'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }
}
