<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Video;
use App\Service\Slugifier;
use App\Entity\SnowboardTrick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Data\SnowboardImageData;
use App\DataFixtures\Data\SnowboardTrickData;
use App\DataFixtures\Data\SnowboardVideoData;

class SnowboardTrickFixtures extends Fixture
{
    public const TRICK_REFERENCE = 'snowboard-trick';

    /**
     * Load and save snowboard tricks.
     */
    public function load(ObjectManager $manager): void
    {
        $batchSize = 5;
        $i = 0;
        foreach (SnowboardTrickData::DATA as $data) {
            $trick = $this->createTrick($data);
            $manager->persist($trick);
            $this->addReference(self::TRICK_REFERENCE . '-' . $i, $trick);

            if (($i % $batchSize) === 0) {
                $manager->flush();
                $manager->clear();
            }

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
        $datetime = new \DateTimeImmutable('now');

        $trick->setName($data[0])
            ->setSlug(Slugifier::slugify($data[0]))
            ->setDescription($data[2])
            ->setCategory($data[1])
            ->setCreatedAt($datetime)
            ->setUpdatedAt($datetime)
            ->setIllustration($this->createImage())
        ;

        for ($i = 0; $i < mt_rand(2, 3); $i++) {
            $video = $this->createVideo();
            $trick->addVideo($video);
        }

        for ($i = 0; $i < mt_rand(4, 6); $i++) {
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
        $image = new Image();
        $url = SnowboardImageData::getRandomData();
        return $image->setFormat((string) $this->getMimeType($url))
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
}
