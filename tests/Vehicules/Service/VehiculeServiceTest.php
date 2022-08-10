<?php

namespace App\Tests\Vehicules\Service;

use App\Entity\Vehicule;
use Faker\Factory;

class VehiculeServiceTest
{
    /**
     * @param $client
     * @return Vehicule
     */
    public function createVehicule($client): Vehicule
    {
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $faker = Factory::create('en-EN');
        $data = [
            'type'           => 'motorcycle',
            'identification' => $faker->creditCardNumber(),
            'brand'          => 'Suzuki',
            'reference'      => 'GSF 650',
            'modelyear'      => $faker->numberBetween(1900, 2022)
        ];

        $vehicule = new Vehicule();
        $vehicule->setType($data['type'])
            ->setIdentification($data['identification'])
            ->setBrand($data['brand'])
            ->setReference($data['reference'])
            ->setModelyear($data['modelyear'])
        ;

        $em->persist($vehicule);
        $em->flush();

        return $vehicule;
    }

    /**
     * @param $client
     * @param $content
     * @return void
     */
    public function deleteVehicule($client, $content): void
    {
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $vehicule = $em->getRepository('App\Entity\Vehicule')->find($content['id'] ?? $content);

        $em->remove($vehicule);
        $em->flush();
    }
}
