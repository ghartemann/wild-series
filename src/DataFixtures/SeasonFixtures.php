<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
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
                $slug = $this->slugify->generate("Saison " . $s);

                $season = new Season();
                $season
                    ->setYear($faker->year())
                    ->setDescription($faker->paragraphs(3, true))
                    ->setProgram($this->getReference('program_' . $p))
                    ->setNumber($s)
                    ->setSlug($slug);

                $manager->persist($season);
                $this->addReference('program_' . $p . '_season_' . $s, $season);
            }
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
