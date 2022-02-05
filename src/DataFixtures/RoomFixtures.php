<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $room = new Room();
        $room->setName("room 1");
        $manager->persist($room);

        $room = new Room();
        $room->setName("room 2");
        $manager->persist($room);

        $room = new Room();
        $room->setName("room 3");
        $manager->persist($room);

        $room = new Room();
        $room->setName("room 4");
        $manager->persist($room);

        $room = new Room();
        $room->setName("room 5");
        $manager->persist($room);

        $manager->flush();
    }
}
