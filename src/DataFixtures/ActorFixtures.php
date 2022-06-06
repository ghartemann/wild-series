<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($a = 1; $a <= 30; $a++) {
            $actor = new Actor();
            $actor
                ->setName($faker->firstName($gender = 'male' | 'female') . " " . $faker->lastName)
                ->setBiography($faker->paragraph(3));
            for ($p = 0; $p < 3; $p++) {
                $actor
                    ->addProgram($this->getReference('program_' . $faker->numberBetween(1, 5)));
            }

            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
