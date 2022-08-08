<?php

namespace App\Tests\Vehicules;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class deleteVehiculeTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    /** @test */
    public function deleteVehiculeTest()
    {
        $client = static::createClient();
        $client->request(
            'DELETE',
            'http://localhost:8080/api/vehicule/2/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }

    /** @test */
    public function deleteVehiculeNotFoundTest()
    {
        $client = static::createClient();
        $client->request(
            'DELETE',
            'http://localhost:8080/api/vehicule/122/delete'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertNotEmpty($content);
        print_r($content);
    }
}
