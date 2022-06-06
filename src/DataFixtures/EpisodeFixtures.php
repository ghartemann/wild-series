<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($p = 1; $p <= 5; $p++) {
            for ($s = 1; $s <= 5; $s++) {
                for ($e = 1; $e <= 10; $e++) {
                    $title = substr($faker->sentence(), 0, -1);
                    $slug = $this->slugify->generate($title);

                    $episode = new Episode();
                    $episode
                        ->setTitle($title)
                        ->setSeason($this->getReference('program_' . $p . '_season_' . $s))
                        ->setNumber($e)
                        ->setSynopsis($faker->paragraphs(1, true))
                        ->setSlug($slug);

                    $manager->persist($episode);
                }
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
