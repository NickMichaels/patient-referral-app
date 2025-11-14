<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // This is just to make an initial ROLE_ADMIN and a ROLE_USER USER 
        $user = new User;
        $user->setEmail("your_email_here@example.com");
        $user->setRoles(["ROLE_ADMIN"]);
        // You can run php bin/console security:hash-password
        // and follow the prompts to hash your own pw
        $user->setPassword('your_hashed_pw_here');
        $manager->persist($user);

        $user = new User;
        $user->setEmail("another_email_here@example.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword('your_hashed_pw_here');
        $manager->persist($user);

        $manager->flush();
    }
}
