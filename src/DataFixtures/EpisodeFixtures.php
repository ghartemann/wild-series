<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($s = 1; $s <= 5; $s++) {
            for ($e = 1; $e <= 10; $e++) {
                $episode = new Episode();
                $episode
                    ->setTitle($faker->sentence())
                    ->setSeason($this->getReference('program_' . $s . '_season_' . $s))
                    ->setNumber($e)
                    ->setSynopsis($faker->paragraphs(1, true));

                $manager->persist($episode);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
