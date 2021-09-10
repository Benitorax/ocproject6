<?php

namespace App\Event\Subscriber;

use App\Event\Event\EmailEvent;
use App\Service\Emails;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailerSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private Emails $emails;

    public function __construct(MailerInterface $mailer, Emails $emails)
    {
        $this->mailer = $mailer;
        $this->emails = $emails;
    }

    public function onEmailEvent(EmailEvent $event): void
    {
        $email = $event->getEmail();
        $this->emails->add($email);
    }

    public function onTerminateEvent(): void
    {
        $emails = $this->emails->getEmails();

        foreach ($emails as $email) {
            $this->mailer->send($email);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvent::class => 'onEmailEvent',
            KernelEvents::TERMINATE => 'onTerminateEvent',
        ];
    }
}
