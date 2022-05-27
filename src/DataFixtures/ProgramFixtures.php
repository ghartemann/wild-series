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
            "title" => "Walking Dead",
            "synopsis" => "Des zombies envahissent la terre",
            "category" => "category_Action",
        ],
        [
            "title" => "The Wire",
            "synopsis" => "Des flics poursuivent des dealers",
            "category" => "category_Policier",
        ],
        [
            'title' => 'The Office',
            'synopsis' => 'Des salariés vendent du papier',
            'category' => 'category_Comédie',
        ],
        [
            'title' => 'Barry',
            'synopsis' => 'Un tueur à gages fait du théâtre',
            'category' => 'category_Comédie',
        ],
        [
            'title' => 'Atlanta',
            'synopsis' => 'Un rappeur et ses potes font du rap',
            'category' => 'category_Comédie',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $programs) {
            $program = new Program();
            $program->setTitle($programs["title"]);
            $program->setSynopsis($programs["synopsis"]);
            $program->setCategory($this->getReference($programs["category"]));
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
