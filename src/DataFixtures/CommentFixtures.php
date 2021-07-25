<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\SnowboardTrick;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\SnowboardTrickFixtures;
use App\Repository\SnowboardTrickRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private SnowboardTrickRepository $trickRepository;
    private UserRepository $userRepository;

    public function __construct(
        SnowboardTrickRepository $trickRepository,
        UserRepository $userRepository
    ) {
        $this->trickRepository = $trickRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Load and save comments.
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getAlltricks() as $trick) {
            for ($i2 = 0; $i2 < mt_rand(10, 15); $i2++) {
                $comment = $this->createComment($trick, $this->getRandomUser());
                $manager->persist($comment);
            }

            $manager->flush();
        }
    }

    /**
     * Create a Comment object.
     */
    private function createComment(SnowboardTrick $trick, User $user): Comment
    {
        $faker = \Faker\Factory::create();

        return (new Comment())
            ->setContent((string) implode(' ', $faker->sentences(3)))
            ->setCreatedAt(
                \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years', 'now'))
            )
            ->setSnowboardTrick($trick)
            ->setUser($user)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SnowboardTrickFixtures::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return ['comment'];
    }

    /**
     * Return all the tricks.
     *
     * @return SnowboardTrick[]
     */
    private function getAlltricks()
    {
        return $this->trickRepository->findAll();
    }

    /**
     * Return a random User.
     */
    private function getRandomUser(): User
    {
        $users = $this->userRepository->findAll();

        return $users[array_rand($users)];
    }
}
