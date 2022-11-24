<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Product;

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

        $product1 = new Product();
        $product1->setTitle("Product 1");
        $product1->setPrice(10.00);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setTitle("Product 2");
        $product2->setPrice(12.99);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setTitle("Product 3");
        $product3->setPrice(49.99);
        $manager->persist($product3);

        $manager->flush();
    }
}
