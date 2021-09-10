<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPublicToken;
use App\Event\Event\EmailEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Mailer
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Send email with token when sign up.
     */
    public function sendSignupEmail(User $user, UserPublicToken $token): void
    {
        $email = $this->createTemplatedEmail('Thanks for signing up!', $user)
            ->htmlTemplate('email/signup.html.twig')
            ->context([
                'token' => $token,
            ])
        ;

        $this->dispatcher->dispatch(new EmailEvent($email));
    }

    /**
     * Send email with token when request to reset a password.
     */
    public function sendResetPasswordRequest(User $user, UserPublicToken $token): void
    {
        $email = $this->createTemplatedEmail('Reset password request', $user)
            ->htmlTemplate('email/reset_password_request.html.twig')
            ->context([
                'token' => $token,
            ])
        ;

        $this->dispatcher->dispatch(new EmailEvent($email));
    }

    /**
     * Create a TemplatedEmail.
     */
    private function createTemplatedEmail(string $subject, User $user): TemplatedEmail
    {
        return (new  TemplatedEmail())
            ->from(new Address('contact@snowtricks.com', 'SnowTricks'))
            ->to(new Address((string) $user->getEmail(), $user->getUsername()))
            ->subject($subject)
        ;
    }
}
