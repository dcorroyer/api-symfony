<?php

namespace App\Tests\Vehicules;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class getVehiculeTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function getVehiculeItemTest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            'http://localhost:8080/api/vehicule/1'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }

    /** @test */
    public function getVehiculeItemNotFoundTest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            'http://localhost:8080/api/vehicule/122'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }
}
