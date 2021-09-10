<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\Mailer;
use App\Entity\UserPublicToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MailerTest extends TestCase
{
    private EventDispatcherInterface $eventDispatcher;
    private Mailer $mailer;

    public function setUp(): void
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->mailer = new Mailer($this->eventDispatcher);
    }

    /**
     * Test if EventDispatcher's dispatch() is executed.
     */
    public function testsendSignupEmail(): void
    {
        $this->eventDispatcher->expects($this->once())->method('dispatch');
        $user = (new User())->setUsername('Sacha')->setEmail('sacha@mail.com');
        $this->mailer->sendSignupEmail($user, new UserPublicToken('token', new \DateTimeImmutable()));
    }

    /**
     * Test if EventDispatcher's dispatch() is executed.
     */
    public function testSendResetPasswordRequest(): void
    {
        $this->eventDispatcher->expects($this->once())->method('dispatch');
        $user = (new User())->setUsername('Sacha')->setEmail('sacha@mail.com');
        $this->mailer->sendResetPasswordRequest($user, new UserPublicToken('token', new \DateTimeImmutable()));
    }
}
