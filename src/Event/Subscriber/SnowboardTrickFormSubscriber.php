<?php

namespace App\Event\Subscriber;

use App\Entity\Image;
use App\Entity\SnowboardTrick;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SnowboardTrickFormSubscriber implements EventSubscriberInterface
{
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

        $images = $trick->getImages();

        foreach ($images as $image) {
            $this->hydrateImage($image, $trick);
        }

        $this->hydrateIllustration($trick);
    }

    /**
     * Set format and data properties in Image object.
     */
    private function hydrateImage(Image $image, SnowboardTrick $trick): Image
    {
        $image->setSnowboardTrick($trick);
        $file = $image->getFile();
        $image->setData($file->getContent());
        $image->setFormat($file->getMimeType());

        return $image;
    }

    /**
     * If illustration is null, then set the first element of images.
     */
    private function hydrateIllustration(SnowboardTrick $trick): SnowboardTrick
    {
        $illustration = $trick->getIllustration();

        if ($illustration instanceof Image) {
            $this->hydrateImage($illustration, $trick);
            return $trick;
        }

        return $trick->setIllustration($trick->getImages()[0]);
    }
}
