<?php

namespace App\DataFixtures;

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
        }

        $manager->flush();
    }
}
