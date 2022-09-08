<?php

namespace App\Tests\Maintenances\Service;

use App\Entity\Maintenance;
use DateTime;
use Faker\Factory;

class MaintenanceServiceTest
{
    /**
     * @param $client
     * @return Maintenance
     */
    public function createMaintenance($client): Maintenance
    {
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $vehicule = $em->getRepository('App\Entity\Vehicule')->find(1);
        $faker = Factory::create('en-EN');
        $data = [
            'type'        => 'maintenance',
            'date'        => new DateTime(),
            'amount'      => $faker->randomFloat(2),
            'description' => $faker->sentence(mt_rand(3, 5)),
            'vehicule'    => $vehicule
        ];

        $maintenance = new Maintenance();
        $maintenance->setType($data['type'])
            ->setDate($data['date'])
            ->setAmount($data['amount'])
            ->setDescription($data['description'])
            ->setVehicule($data['vehicule'])
        ;

        $em->persist($maintenance);
        $em->flush();

        return $maintenance;
    }

    /**
     * @param $client
     * @param $content
     * @return void
     */
    public function deleteMaintenance($client, $content): void
    {
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $maintenance = $em->getRepository('App\Entity\Maintenance')->find($content['id'] ?? $content);

        $em->remove($maintenance);
        $em->flush();
    }
}
