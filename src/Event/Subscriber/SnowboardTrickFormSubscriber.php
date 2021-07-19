<?php

namespace App\Event\Subscriber;

use App\Entity\Image;
use App\Entity\Video;
use App\Entity\SnowboardTrick;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Service\VideoUrlConverter\VideoUrlConverter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SnowboardTrickFormSubscriber implements EventSubscriberInterface
{
    private VideoUrlConverter $converter;

    public function __construct(VideoUrlConverter $converter)
    {
        $this->converter = $converter;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    /**
     * Set trick to images and set illustration if null.
     */
    public function onPostSubmit(FormEvent $event): void
    {
        $trick = $event->getData();

        if (!$trick instanceof SnowboardTrick) {
            return;
        }

        foreach ($trick->getImages() as $image) {
            $this->hydrateImage($image);
        }

        $this->hydrateIllustration($trick);
        $this->hydrateVideos($trick->getVideos());
    }

    /**
     * Set format and data properties in Image object.
     */
    private function hydrateImage(Image $image): void
    {
        $file = $image->getFile();
        $image->setData($file->getContent())
            ->setFormat($file->getMimeType());
    }

    /**
     * If illustration is null, then set the first element of images.
     */
    private function hydrateIllustration(SnowboardTrick $trick): void
    {
        $illustration = $trick->getIllustration();

        if ($illustration instanceof Image) {
            $this->hydrateImage($illustration);
            return;
        }

        $trick->setIllustration($trick->getImages()[0]);
    }

    /**
     * Set source and embed url in every Video object.
     */
    private function hydrateVideos(iterable $videos): void
    {
        foreach ($videos as $video) {
            [$source, $url] = $this->converter->convert((string) $video->getTagOrUrl());
            $video->setSource($source)->setUrl($url);
        }
    }
}
