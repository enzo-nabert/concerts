<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {

        //USER
        $user = new User();
        $user->setFirstName("user1");
        $user->setLastName("user1");
        $user->setEmail("user@mailbox.com");
        $user->setPassword($this->hasher->hashPassword($user,"azertyuiop"));
        $user->setRoles([User::ROLE_USER]);

        //ADMIN
        $admin = new User();
        $admin->setFirstName("enzo");
        $admin->setLastName("nabert");
        $admin->setEmail("nabertenzo@gmail.com");
        $admin->setPassword($this->hasher->hashPassword($admin,"Password123*"));
        $admin->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();
    }
}
