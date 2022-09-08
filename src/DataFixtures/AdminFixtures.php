<?php

namespace App\DataFixtures;

use App\Entity\Maintenance;
use App\Entity\User;
use App\Entity\Vehicule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasherInterface
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en-EN');
        $admin = new User();
        $hash = $this->hasherInterface->hashPassword($admin, "password");

        $admin->setEmail("admin@api.com")
            ->setPassword($hash)
            ->setRoles(["ROLE_ADMIN"])
        ;

        $manager->persist($admin);

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
                ->setUser($admin)
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
                    ->setAmount($faker->randomFloat(2))
                    ->setDescription($faker->sentence(mt_rand(3, 5)))
                    ->setVehicule($vehicule)
                ;

                $manager->persist($maintenance);
            }
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}
