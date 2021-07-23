<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Data\AvatarImageData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
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
            $manager->persist($user->getAvatar()); /** @phpstan-ignore-line */
            $this->addReference(self::USER_REFERENCE . '-' . $i, $user);
            $i++;
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
        $name = $faker->firstName();
        $user = new User();

        return $user->setUsername($name)
            ->setEmail($name . '@yopmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
            ->setIsActivated(true)
            ->setAvatar($this->createAvatar())
        ;
    }

    /**
     * Return an Image object for User avatar.
     */
    private function createAvatar(): Image
    {
        $url = AvatarImageData::getRandomData();
        return (new Image())->setFormat((string) $this->getMimeType($url))
            ->setData((string) file_get_contents($url))
            ->setCreatedAt(new \DateTimeImmutable('now'))
        ;
    }

    /**
     * Return the mime type from file_get_contents.
     *
     * @return false|string
     */
    private function getMimeType(string $url)
    {
        return (new \finfo(FILEINFO_MIME_TYPE))->buffer((string) file_get_contents($url));
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
