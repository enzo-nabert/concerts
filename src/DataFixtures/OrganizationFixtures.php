<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Organization;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganizationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $roomRepository = $manager->getRepository(Room::class);
        $rooms = $roomRepository->findAll();

        $orga = new Organization();
        $orga->setName("orga 1");
        $orga->setAddress("1 rue là bas");
        $orga->addRoom($rooms[0]);
        $orga->addRoom($rooms[1]);
        $orga->addRoom($rooms[2]);
        $manager->persist($orga);

        $orga = new Organization();
        $orga->setName("orga 2");
        $orga->setAddress("1587 avenue ici");
        $orga->addRoom($rooms[3]);
        $orga->addRoom($rooms[4]);
        $manager->persist($orga);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
           RoomFixtures::class
        ];
    }
}
