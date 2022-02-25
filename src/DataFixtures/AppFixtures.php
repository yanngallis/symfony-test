<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $administrator = new User();

        $administrator->setEmail("admin@test.com");
        $administrator->setRoles(["ROLE_ADMIN"]);
        $administrator->setPassword($this->hasher->hashPassword($administrator, 'password'));

        $manager->persist($administrator);
        $manager->flush();


        $manager->flush();
    }
}
