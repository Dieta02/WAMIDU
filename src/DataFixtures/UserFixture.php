<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // ...

    // ...
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('ADMIN');
        $user->setSurname('admin');
        $user->setEmail('admin@gmail.com');
        $password = $this->hasher->hashPassword($user, 'wamidu');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}