<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPublicToken;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendSignupEmail(User $user, UserPublicToken $token): void
    {
        $email = $this->createTemplatedEmail('Thanks for signing up!', $user)
            ->htmlTemplate('email/signup.html.twig')
            ->context([
                'token' => $token,
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendResetPasswordRequest(User $user, UserPublicToken $token): void
    {
        $email = $this->createTemplatedEmail('Reset password request', $user)
            ->htmlTemplate('email/reset_password_request.html.twig')
            ->context([
                'token' => $token,
            ])
        ;

        $this->mailer->send($email);
    }

    private function createTemplatedEmail(string $subject, User $user): TemplatedEmail
    {
        return (new  TemplatedEmail())
            ->from(new Address('contact@snowtricks.com', 'SnowTricks'))
            ->to(new Address((string) $user->getEmail(), $user->getUsername()))
            ->subject($subject)
        ;
    }
}
