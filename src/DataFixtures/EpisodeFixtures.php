<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i < 11; $i++) {
            $episode = new Episode();
            $episode
                ->setTitle($faker->sentence(5))
                ->setSeason($this->getReference('season_' . $faker->numberBetween(1, 10)))
                ->setNumber($i)
                ->setSynopsis($faker->paragraphs(3, true));
            $manager->persist($episode);
        }
        $manager->flush();
    }
}
