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
        $room2 = $roomRepo->findOneBy(["name" => "room 2"]);
        $room3 = $roomRepo->findOneBy(["name" => "room 3"]);

        $concert = new Concert();
        $concert->setName("le concert de 2023");
        $concert->setDate(\DateTime::createFromFormat('d-m-Y','24-12-2023'));
        $concert->setBegin(\DateTime::createFromFormat('H:i:s','18:00:00'));
        $concert->setEnd(\DateTime::createFromFormat('H:i:s','23:00:00'));
        $concert->setPicture("https://via.placeholder.com/150x150");
        $concert->setCapacity(1200);
        $concert->setRoom($room);

        $manager->persist($concert);

        $concert = new Concert();
        $concert->setName("le concert de 2021");
        $concert->setDate(\DateTime::createFromFormat('d-m-Y','06-04-2021'));
        $concert->setBegin(\DateTime::createFromFormat('H:i:s','18:00:00'));
        $concert->setEnd(\DateTime::createFromFormat('H:i:s','23:00:00'));
        $concert->setPicture("https://via.placeholder.com/150x150");
        $concert->setCapacity(1200);
        $concert->setRoom($room);

        $manager->persist($concert);

        $concert = new Concert();
        $concert->setName("le concert de 2022");
        $concert->setDate(\DateTime::createFromFormat('d-m-Y','19-06-2022'));
        $concert->setBegin(\DateTime::createFromFormat('H:i:s','15:00:00'));
        $concert->setEnd(\DateTime::createFromFormat('H:i:s','17:30:00'));
        $concert->setPicture("https://via.placeholder.com/150x150");
        $concert->setCapacity(379);
        $concert->setRoom($room2);

        $manager->persist($concert);

        $concert = new Concert();
        $concert->setName("le concert de 2020");
        $concert->setDate(\DateTime::createFromFormat('d-m-Y','15-10-2020'));
        $concert->setBegin(\DateTime::createFromFormat('H:i:s','06:00:00'));
        $concert->setEnd(\DateTime::createFromFormat('H:i:s','15:00:00'));
        $concert->setPicture("https://via.placeholder.com/150x150");
        $concert->setCapacity(2500);
        $concert->setRoom($room3);

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
