<?php

namespace App\DataFixtures;

use App\Entity\Concert;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConcertFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $roomRepo = $manager->getRepository(Room::class);
        $room = $roomRepo->findOneBy(["name" => "room 1"]);

        $concert = new Concert();
        $concert->setName("le concert du siècle");
        $concert->setDate(\DateTime::createFromFormat('d-m-Y','24-12-2021'));
        $concert->setBegin(\DateTime::createFromFormat('H:i:s','18:00:00'));
        $concert->setEnd(\DateTime::createFromFormat('H:i:s','23:00:00'));
        $concert->setPicture("https://via.placeholder.com/150x150");
        $concert->setCapacity(1200);
        $concert->setRoom($room);

        $manager->persist($concert);

        $concert = new Concert();
        $concert->setName("le concert du siècle");
        $concert->setDate(\DateTime::createFromFormat('d-m-Y','24-12-2021'));
        $concert->setBegin(\DateTime::createFromFormat('H:i:s','18:00:00'));
        $concert->setEnd(\DateTime::createFromFormat('H:i:s','23:00:00'));
        $concert->setPicture("https://via.placeholder.com/150x150");
        $concert->setCapacity(1200);
        $concert->setRoom($room);

        $manager->persist($concert);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoomFixtures::class
        ];
    }
}
