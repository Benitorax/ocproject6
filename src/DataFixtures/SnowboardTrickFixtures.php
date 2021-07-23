<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Category;
use App\Service\Slugifier;
use App\Entity\SnowboardTrick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Data\SnowboardImageData;
use App\DataFixtures\Data\SnowboardTrickData;
use App\DataFixtures\Data\SnowboardVideoData;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class SnowboardTrickFixtures extends Fixture implements FixtureGroupInterface
{
    public const TRICK_REFERENCE = 'snowboard-trick';

    /**
     * Load and save snowboard tricks.
     */
    public function load(ObjectManager $manager): void
    {
        $batchSize = 3;
        $i = 0;
        foreach (SnowboardTrickData::DATA as $data) {
            $trick = $this->createTrick($data);
            $manager->persist($trick);

            if (($i % $batchSize) === 0) {
                $manager->flush();
                $manager->clear();
            }

            $this->addReference(self::TRICK_REFERENCE . '-' . $i, $trick);
            $i++;
        }

        $manager->flush();
        $manager->clear();
    }

    /**
     * Create snowboard trick object.
     */
    private function createTrick(array $data): SnowboardTrick
    {
        $trick = new SnowboardTrick();
        $faker = \Faker\Factory::create();

        $trick->setName($data[0])
            ->setSlug(Slugifier::slugify($data[0]))
            ->setDescription($data[2])
            ->setCategory(Category::$categories[$data[1]])
            ->setCreatedAt(
                \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years', '-2 year'))
            )
            ->setUpdatedAt(
                \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 year', '-5 weeks'))
            )
            ->setIllustration($this->createImage())
        ;

        for ($i = 0; $i < mt_rand(2, 3); $i++) {
            $video = $this->createVideo();
            $trick->addVideo($video);
        }

        for ($i = 0; $i < mt_rand(3, 4); $i++) {
            $image = $this->createImage();
            $trick->addImage($image);
        }

        return $trick;
    }

    /**
     * Create Image object.
     */
    private function createImage(): Image
    {
        $url = SnowboardImageData::getRandomData();
        return (new Image())->setFormat((string) $this->getMimeType($url))
            ->setData((string) file_get_contents($url))
            ->setCreatedAt(new \DateTimeImmutable('now'))
        ;
    }

    /**
     * Create Video object.
     */
    private function createVideo(): Video
    {
        $data = SnowboardVideoData::getRandomData();

        return (new Video())
            ->setSource($data[0])
            ->setUrl($data[1])
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

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return ['trick'];
    }
}
