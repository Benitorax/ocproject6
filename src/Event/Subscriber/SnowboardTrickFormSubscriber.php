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

    public function onPostSubmit(FormEvent $event): void
    {
        $trick = $event->getData();

        if (!$trick instanceof SnowboardTrick) {
            return;
        }

        $illustration = $trick->getIllustration();

        if (!$illustration instanceof Image) {
            return;
        }

        $illustration->setSnowboardTrick($trick);
        $file = $illustration->getFile();
        $illustration->setData($file->getContent());
        $illustration->setData($file->getMimeType());
    }
}
