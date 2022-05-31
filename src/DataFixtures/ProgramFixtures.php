<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            "title" => "Wire (The)",
            "synopsis" => "Des flics poursuivent des dealers, ACAB mais on les aime bien quand même",
            "category" => "Policier",
        ],
        [
            'title' => 'Office (The)',
            'synopsis' => 'Des salariés vendent du papier',
            'category' => 'Comédie',
        ],
        [
            'title' => 'Barry',
            'synopsis' => 'Un tueur à gages fait du théâtre',
            'category' => 'Comédie',
        ],
        [
            'title' => 'Atlanta',
            'synopsis' => "Les blanc.he.s sont mal à l'aise",
            'category' => 'Comédie',
        ],
        [
            'title' => 'Watchmen',
            'synopsis' => "Clairement encore un mauvais moment pour les blanc.he.s",
            'category' => 'Comédie',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $key => $programName) {
            $key = $key + 1;

            $program = new Program();
            $program
                ->setTitle($programName["title"])
                ->setSynopsis($programName["synopsis"])
                ->setCategory($this->getReference("category_" . $programName["category"]));

            $this->addReference('program_' . $key, $program);
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
