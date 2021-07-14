<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserToken;
use App\Entity\UserPublicToken;
use App\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserTokenManager
{
    /**
     * The first 20 characters of the token are a "selector".
     */
    private const SELECTOR_LENGTH = 20;
    private const SIGNING_KEY = 'reset_password';
    private const INVALID_TOKEN_MESSAGE = 'The link is invalid.';
    private const EXPIRED_TOKEN_MESSAGE = 'The link in your email is expired.';

    /**
     * How long a token is valid in seconds
     */
    private int $resetRequestLifetime = 60 * 60;

    private EntityManagerInterface $entityManager;
    private UserTokenRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserTokenRepository $repository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * Creates a UserToken and returns a public token.
     */
    public function create(int $type, User $user): UserPublicToken
    {
        $expiredAt = new \DateTimeImmutable(sprintf('+%d seconds', $this->resetRequestLifetime));
        $selector = $this->getRandomAlphaNumStr();
        $verifier = $this->getRandomAlphaNumStr();
        $hashedToken = $this->getHashedToken($expiredAt, (int) $user->getId(), $verifier);
        $token = new UserToken($type, $user, $expiredAt, $selector, $hashedToken);
        $this->ensureOneTokenInDatabase($token);

        return new UserPublicToken($selector . $verifier, $expiredAt);
    }

    public function validateTokenAndFetchUser(int $type, string $token): User
    {
        if (40 !== \strlen($token)) {
            throw $this->createException($type, self::INVALID_TOKEN_MESSAGE);
        }

        $userToken = $this->getUserToken($type, $token);

        if (null === $userToken) {
            throw $this->createException($type, self::INVALID_TOKEN_MESSAGE);
        }

        if ($userToken->isExpired()) {
            throw $this->createException($type, self::EXPIRED_TOKEN_MESSAGE);
        }

        $user = $userToken->getUser();

        if (!$user instanceof User) {
            throw $this->createException($type, self::INVALID_TOKEN_MESSAGE);
        }

        $hashedToken = $this->getHashedToken(
            $userToken->getExpiredAt(),
            (int) $user->getId(),
            substr($token, self::SELECTOR_LENGTH)
        );

        if (false === hash_equals($userToken->getHashedToken(), $hashedToken)) {
            throw $this->createException($type, self::INVALID_TOKEN_MESSAGE);
        }

        return $user;
    }

    private function createException(int $type, string $message): \Exception
    {
        if (UserToken::SIGNUP === $type) {
            return new \Exception($message . ' First create an account.');
        }

        return new \Exception($message . ' Please request to reset your password again.');
    }

    /**
     * Original credit to Laravel's Str::random() method.
     *
     * String length is 20 characters
     */
    private function getRandomAlphaNumStr(): string
    {
        $string = '';

        while (($len = strlen($string)) < 20) {
            $size = 20 - $len;

            $bytes = random_bytes($size);

            $string .= substr(
                str_replace(['/', '+', '='], '', base64_encode($bytes)),
                0,
                $size
            );
        }

        return $string;
    }

    /**
     * Returns a hashed token.
     */
    private function getHashedToken(\DateTimeInterface $expiredAt, int $userId, string $verifier = null): string
    {
        $encodedData = json_encode([$verifier, $userId, $expiredAt->getTimestamp()]);

        return base64_encode(hash_hmac('sha256', (string) $encodedData, self::SIGNING_KEY, true));
    }

    /**
     * Returns a UserToken or null.
     */
    private function getUserToken(int $type, string $token): ?UserToken
    {
        $selector = substr($token, 0, self::SELECTOR_LENGTH);

        return $this->repository->findOneBy(['type' => $type, 'selector' => $selector]);
    }

    private function ensureOneTokenInDatabase(UserToken $token): void
    {
        $this->deleteTokensFromUser($token->getUser());
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }

    public function deleteTokensFromUser(User $user): void
    {
        $tokens = $user->getTokens();

        foreach ($tokens as $token) {
            $this->entityManager->remove($token);
        }

        $this->entityManager->flush();
    }
}
