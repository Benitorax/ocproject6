<?php

namespace App\Event\Subscriber;

use App\Entity\Image;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AvatarFormSubscriber implements EventSubscriberInterface
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
        $image = $event->getData()['image'];

        if (!$image instanceof Image) {
            return;
        }

        $this->hydrateImage($image);
    }

    /**
     * Set format and data properties in Image object.
     */
    private function hydrateImage(Image $image): void
    {
        $file = $image->getFile();
        if (!$file instanceof UploadedFile) {
            return;
        }

        $image->setData($file->getContent())
            ->setFormat((string) $file->getMimeType())
            ->setFile(null);
    }
}
