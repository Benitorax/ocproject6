<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Image;
use App\Service\Mailer;
use App\Entity\UserToken;
use App\Service\UserManager;
use App\Entity\UserPublicToken;
use PHPUnit\Framework\TestCase;
use App\Service\UserTokenManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserManagerTest extends TestCase
{
    private UserPasswordHasher $passwordHasher;
    private EntityManagerInterface $entityManager;
    private UserTokenManager $tokenManager;
    private Mailer $mailer;
    private UserManager $manager;

    public function setUp(): void
    {
        $this->passwordHasher = $this->createMock(UserPasswordHasher::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->tokenManager = $this->createMock(UserTokenManager::class);
        $this->mailer = $this->createMock(Mailer::class);

        $this->manager = new UserManager(
            $this->passwordHasher,
            $this->entityManager,
            $this->tokenManager,
            $this->mailer
        );
    }

    public function testSaveNewUser(): void
    {
        $user = (new User())->setPassword('123456');
        $userToken = new UserPublicToken('azerty123', new \DateTimeImmutable('now'));
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');
        $this->tokenManager->expects($this->once())->method('create')->willReturn($userToken);
        $this->mailer->expects($this->once())->method('sendSignupEmail');

        $this->manager->saveNewUser($user);
    }

    public function testActivate(): void
    {
        $user = new User();
        $this->tokenManager->expects($this->once())->method('deleteTokensFromUser');
        $this->entityManager->expects($this->once())->method('flush');
        $this->manager->activate($user);
    }

    public function testmanageResetPasswordRequest(): void
    {
        $user = new User();
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())->method('findOneBy')->willReturn($user);
        $userToken = new UserPublicToken('azerty123', new \DateTimeImmutable('now'));
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($userRepository);
        $this->tokenManager->expects($this->once())->method('create')->willReturn($userToken);
        $this->mailer->expects($this->once())->method('sendResetPasswordRequest');

        $this->manager->manageResetPasswordRequest('Sacha');
    }

    public function testValidateTokenAndFetchUser(): void
    {
        $user = new User();
        $this->tokenManager->expects($this->once())->method('validateTokenAndFetchUser')->willReturn($user);
        $value = $this->manager->validateTokenAndFetchUser(UserToken::RESET_PASSWORD, 'azerty123');
        $this->assertSame($user, $value);
        $this->assertInstanceOf(User::class, $value);
    }

    public function testModifyAvatar(): void
    {
        $this->entityManager->expects($this->once())->method('remove');
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');
        $user = (new User())->setAvatar(new Image());

        $this->manager->modifyAvatar($user, new Image());
    }

    public function testDeleteAvatar(): void
    {
        $this->entityManager->expects($this->once())->method('remove');
        $this->entityManager->expects($this->once())->method('flush');
        $user = (new User())->setAvatar(new Image());

        $this->manager->deleteAvatar($user);
    }
}
