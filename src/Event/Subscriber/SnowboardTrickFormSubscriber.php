<?php

namespace App\Event\Subscriber;

use App\Entity\Image;
use App\Entity\Video;
use App\Service\Slugifier;
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

        $this->hydrateIllustration($trick, $event->getForm()['hasIllustration']->getData());
        $this->hydrateVideos($trick->getVideos());
        $trick->setSlug(Slugifier::slugify((string) $trick->getName()));
    }

    /**
     * Set format and data properties in Image object.
     */
    private function hydrateImage(Image $image): void
    {
        $file = $image->getFile();

        if (null === $file) {
            return;
        }

        $image->setData($file->getContent())
            ->setFormat($file->getMimeType());
    }

    /**
     * If illustration is null, then set the first element of images.
     */
    private function hydrateIllustration(SnowboardTrick $trick, bool $hasIllustration): void
    {
        if ($hasIllustration) {
            $illustration = $trick->getIllustration();

            if ($illustration instanceof Image) {
                $this->hydrateImage($illustration);
            }

            return;
        }

        if ($trick->getImages()[0] instanceof Image) {
            $trick->setIllustration(clone $trick->getImages()[0]);
        }
    }

    /**
     * Set source and embed url in every Video object.
     * @param Video[] $videos
     */
    private function hydrateVideos(iterable $videos): void
    {
        foreach ($videos as $video) {
            $data = $this->converter->convert((string) $video->getTagOrUrl());
            if (null !== $data) {
                $video->setSource($data['source'])->setUrl($data['url']);
            }
        }
    }
}
