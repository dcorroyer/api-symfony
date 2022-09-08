<?php

namespace App\Tests\Vehicules\Service;

use App\Repository\UserRepository;
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
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail('admin@api.com');
        $data = [
            'type'           => 'motorcycle',
            'identification' => $faker->creditCardNumber(),
            'brand'          => 'Suzuki',
            'reference'      => 'GSF 650',
            'modelyear'      => $faker->numberBetween(1900, 2022),
            'user'           => $user
        ];

        $vehicule = new Vehicule();
        $vehicule->setType($data['type'])
            ->setIdentification($data['identification'])
            ->setBrand($data['brand'])
            ->setReference($data['reference'])
            ->setModelyear($data['modelyear'])
            ->setUser($data['user'])
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
