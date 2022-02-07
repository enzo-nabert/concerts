<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Band;
use App\Entity\Concert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BandFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $artistRepository = $manager->getRepository(Artist::class);
        $concertRepository = $manager->getRepository(Concert::class);
        $concerts = $concertRepository->findAll();

        //band 1
        $b = new Band();
        $b->setName("les frères terrieur");

        $artists = $artistRepository->findBy(array('surname' => 'terrieur'));

        foreach ($artists as $artist) {
            $b->addArtist($artist);
        }
        $b->addConcert($concerts[0]);

        $manager->persist($b);


        //band 2
        $b = new Band();
        $b->setName("les autres");

        $artists = $artistRepository->findAll();

        foreach ($artists as $artist) {
            if ($artist->getSurname() !== "terrieur") {
                $b->addArtist($artist);
            }
        }
        $b->addConcert($concerts[1]);

        $manager->persist($b);
        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            ArtistFixtures::class,
            ConcertFixtures::class
        ];
    }
}
