<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\UserToken;
use App\Entity\UserPublicToken;
use App\Service\UserTokenManager;
use App\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTokenManagerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private UserTokenRepository $repository;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(UserTokenRepository::class);

        $this->manager = new UserTokenManager($this->entityManager, $this->repository);
    }

    public function testCreate(): void
    {
        $user = new User();
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, 1);

        $this->repository->expects($this->once())->method('findAllExpired')->willReturn([]);
        $this->manager->create(UserToken::SIGNUP, $user);
    }

    public function testValidateTokenAndFetchUser(): void
    {
        $user = (new User())->setUsername('sacha')
                        ->setEmail('sacha@mail.com')
                        ->setPassword('123456')
        ;

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // clean database
        $purger = new ORMPurger($entityManager);
        $purger->purge();

        $entityManager->persist($user);
        $entityManager->flush();

        // when sign up
        $manager = static::getContainer()->get(UserTokenManager::class);
        $publicToken = $manager->create(UserToken::SIGNUP, $user);
        $this->assertInstanceOf(UserPublicToken::class, $publicToken);
        $fetchedUser = $manager->validateTokenAndFetchUser(UserToken::SIGNUP, $publicToken->token);
        $this->assertInstanceOf(User::class, $fetchedUser);
        $this->assertSame($user, $fetchedUser);

        // when reset password
        $publicToken = $manager->create(UserToken::RESET_PASSWORD, $user);
        $this->assertInstanceOf(UserPublicToken::class, $publicToken);
        $fetchedUser = $manager->validateTokenAndFetchUser(UserToken::RESET_PASSWORD, $publicToken->token);
        $this->assertInstanceOf(User::class, $fetchedUser);
        $this->assertSame($user, $fetchedUser);

        // clean database
        $purger = new ORMPurger($entityManager);
        $purger->purge();
    }

    public function testDeleteTokensFromUser(): void
    {
        $user = new User();
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('tokens');
        $property->setAccessible(true);
        $property->setValue($user, new ArrayCollection([1,2,3]));
        $this->entityManager->expects($this->exactly(3))->method('remove');
        $this->entityManager->expects($this->once())->method('flush');
        $this->manager->deleteTokensFromUser($user);
    }
}
