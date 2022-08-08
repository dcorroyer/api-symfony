<?php

namespace App\DataFixtures;

use App\Entity\Maintenance;
use App\Entity\Vehicule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en-EN');

        for ($v = 0; $v < mt_rand(5, 10); $v++) {
            $vehicule = new Vehicule();

            $vehicule->setType($faker->randomElement([
                    Vehicule::TYPE['CAR'],
                    Vehicule::TYPE['MOTORCYCLE'],
                    Vehicule::TYPE['SCOOTER']
                ]))
                ->setIdentification($faker->creditCardNumber())
                ->setBrand($faker->name())
                ->setReference($faker->name())
                ->setModelyear($faker->numberBetween(1900, 2022))
            ;

            $manager->persist($vehicule);

            for ($m = 0; $m < mt_rand(5, 10); $m++) {
                $maintenance = new Maintenance();

                $maintenance->setType($faker->randomElement([
                        Maintenance::TYPE['MAINTENANCE'],
                        Maintenance::TYPE['REPAIR'],
                        Maintenance::TYPE['RESTORATION']
                    ]))
                    ->setDate($faker->dateTime())
                    ->setAmount($faker->randomFloat())
                    ->setDescription($faker->sentence(mt_rand(3, 5)))
                    ->setVehicule($vehicule)
                ;

                $manager->persist($maintenance);
            }
        }

        $manager->flush();
    }
}
