<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 25; $i++) {
            $season = new Season();
            $season
                ->setNumber($faker->numberBetween(1, 10))
                ->setYear($faker->year())
                ->setDescription($faker->paragraphs(3, true))
                ->setProgram($this->getReference('program_' . $faker->numberBetween(0, 4)));
            $this->addReference('season_' . $faker->numberBetween(1, 10), $season);
            $manager->persist($season);
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
