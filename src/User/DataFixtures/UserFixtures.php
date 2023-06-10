<?php

namespace App\User\DataFixtures;

use App\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('john@example.com');
        $user->setPassword('secret123');
        $manager->persist($user);

        $manager->flush();

        $this->addReference('admin-user', $user);
    }
}
