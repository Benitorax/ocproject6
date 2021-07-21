<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\SnowboardTrick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMMENT_REFERENCE = 'comment';

    /**
     * Load and save comments.
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 24; $i++) {
            /** @var SnowboardTrick */
            $trick = $this->getReference(SnowboardTrickFixtures::TRICK_REFERENCE . '-' . $i);

            for ($i2 = 0; $i2 < mt_rand(10, 15); $i2++) {
                $comment = $this->createComment($trick);
                $manager->persist($comment);
            }

            $manager->flush();
            $manager->clear();
        }
    }

    /**
     * Create a Comment object.
     */
    private function createComment(SnowboardTrick $trick): Comment
    {
        $faker = \Faker\Factory::create();
        /** @var User */
        $user = $this->getReference(UserFixtures::USER_REFERENCE . '-' . mt_rand(0, 9));

        return (new Comment())
            ->setContent((string) implode(' ', $faker->sentences(3)))
            ->setCreatedAt(
                \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now'))
            )
            ->setSnowboardTrick($trick)
            ->setUser($user)
        ;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SnowboardTrickFixtures::class
        ];
    }
}
