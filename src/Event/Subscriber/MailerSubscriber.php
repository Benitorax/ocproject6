<?php

namespace App\Event\Subscriber;

use App\Service\Emails;
use Psr\Log\LoggerInterface;
use App\Event\Event\EmailEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private Emails $emails;
    private LoggerInterface $mailerLogger;

    public function __construct(MailerInterface $mailer, Emails $emails, LoggerInterface $mailerLogger)
    {
        $this->mailer = $mailer;
        $this->emails = $emails;
        $this->mailerLogger = $mailerLogger;
    }

    /**
     * Add email to emails logger.
     */
    public function onEmailEvent(EmailEvent $event): void
    {
        $email = $event->getEmail();
        $this->emails->add($email);
    }

    /**
     * Send emails.
     */
    public function onTerminateEvent(): void
    {
        $emails = $this->emails->getEmails();

        foreach ($emails as $email) {
            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $exception) {
                $this->logEmailException($exception, $email);
            }
        }
    }

    /**
     * Log email exception.
     */
    public function logEmailException(TransportExceptionInterface $exception, TemplatedEmail $email): void
    {
        $addresses = $email->getTo();
        $stringAddresses = [];

        if (is_array($addresses)) {
            foreach ($addresses as $address) {
                $stringAddresses[] = $address->getAddress();
            }
        } else { /** @phpstan-ignore-line */
            $stringAddresses[] = $addresses->getAddress();
        }

        $this->mailerLogger->critical(
            'An error occurred when sending an email (subject: ' . $email->getSubject() . ') to ' .
            implode(', ', $stringAddresses) . '. ' . $exception->getDebug(),
            [
                'logger' => $this->mailerLogger,
                'debug' => $exception->getDebug()
            ]
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvent::class => 'onEmailEvent',
            KernelEvents::TERMINATE => 'onTerminateEvent',
        ];
    }
}
