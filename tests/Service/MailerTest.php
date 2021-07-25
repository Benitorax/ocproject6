<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\Mailer;
use App\Entity\UserPublicToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends TestCase
{
    private MailerInterface $symfonyMailer;
    private Mailer $mailer;

    public function setUp(): void
    {
        $this->symfonyMailer = $this->createMock(MailerInterface::class);
        $this->mailer = new Mailer($this->symfonyMailer);
    }

    public function testsendSignupEmail(): void
    {
        $this->symfonyMailer->expects($this->once())->method('send');
        $user = (new User())->setUsername('Sacha')->setEmail('sacha@mail.com');
        $this->mailer->sendSignupEmail($user, new UserPublicToken('token', new \DateTimeImmutable()));
    }

    public function testSendResetPasswordRequest(): void
    {
        $this->symfonyMailer->expects($this->once())->method('send');
        $user = (new User())->setUsername('Sacha')->setEmail('sacha@mail.com');
        $this->mailer->sendResetPasswordRequest($user, new UserPublicToken('token', new \DateTimeImmutable()));
    }
}
