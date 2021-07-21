<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load and save users.
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = $this->createUser();
            $manager->persist($user);
            $this->addReference(self::USER_REFERENCE . '-' . $i, $user);
        }

        $manager->flush();
        $manager->clear();
    }

    /**
     * Create User object.
     */
    private function createUser(): User
    {
        $faker = \Faker\Factory::create();
        $name = $faker->name();
        $user = new User();

        return $user->setUsername($name)
            ->setEmail($name . '@yopmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
            ->setIsActivated(true)
        ;
    }
}
