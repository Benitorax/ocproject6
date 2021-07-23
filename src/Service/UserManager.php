<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Image;
use App\Service\Mailer;
use App\Entity\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;
    private UserTokenManager $tokenManager;
    private Mailer $mailer;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        UserTokenManager $tokenManager,
        Mailer $mailer
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
        $this->mailer = $mailer;
    }

    /**
     * Save a new user.
     */
    public function saveNewUser(User $user): User
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $token = $this->tokenManager->create(UserToken::SIGNUP, $user);
        $this->mailer->sendSignupEmail($user, $token);

        return $user;
    }

    /**
     * Activate a user.
     */
    public function activate(User $user): void
    {
        $user->setIsActivated(true);
        $this->tokenManager->deleteTokensFromUser($user);
        $this->entityManager->flush();
    }

    /**
     * Manage a reset password request.
     */
    public function manageResetPasswordRequest(string $username): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user instanceof User) {
            return;
        }

        $token = $this->tokenManager->create(UserToken::RESET_PASSWORD, $user);
        $this->mailer->sendResetPasswordRequest($user, $token);
    }

    /**
     * Manage reset password.
     */
    public function manageResetPassword(User $user, string $password): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $this->tokenManager->deleteTokensFromUser($user);
        $this->entityManager->flush();
    }

    /**
     * If token is valid then fetch user.
     */
    public function validateTokenAndFetchUser(int $type, string $token): User
    {
        return $this->tokenManager->validateTokenAndFetchUser($type, $token);
    }

    /**
     * Modify avatar.
     */
    public function modifyAvatar(User $user, Image $image): void
    {
        $this->entityManager->remove($user->getAvatar()); /** @phpstan-ignore-line */
        $user->setAvatar($image);
        $this->entityManager->persist($image);
        $this->entityManager->flush();
    }

    /**
     * Delete avatar.
     */
    public function deleteAvatar(User $user): void
    {
        $this->entityManager->remove($user->getAvatar()); /** @phpstan-ignore-line */
        $user->setAvatar(null);
        $this->entityManager->flush();
    }
}
