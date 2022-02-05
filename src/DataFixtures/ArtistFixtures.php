<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $names = [
            ['firstName' => "croc", 'surname' => 'odile'],
            ['firstName' => "alain", 'surname' => 'terrieur'],
            ['firstName' => "alex", 'surname' => 'terrieur'],
            ['firstName' => "agathe", 'surname' => 'zeblouze'],
            ['firstName' => "jean", 'surname' => 'némard']
        ];

        foreach ($names as $elt) {
            $artist = new Artist();
            $artist->setFirstName($elt['firstName']);
            $artist->setSurname($elt['surname']);

            $manager->persist($artist);
        }


        $manager->flush();
    }
}
