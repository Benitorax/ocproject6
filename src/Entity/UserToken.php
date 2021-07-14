<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserTokenRepository;

/**
 * @ORM\Entity(repositoryClass=UserTokenRepository::class)
 */
class UserToken
{
    public const SIGNUP = 1;
    public const RESET_PASSWORD = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * SIGNUP or RESET_PASSWORD value.
     * @ORM\Column(type="smallint")
     */
    private int $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $selector;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $hashedToken;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $requestedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $expiredAt;

    public function __construct(
        int $type,
        User $user,
        \DateTimeImmutable $expiredAt,
        string $selector,
        string $hashedToken
    ) {
        $this->requestedAt = new \DateTimeImmutable('now');
        $this->type = $type;
        $this->user = $user;
        $this->expiredAt = $expiredAt;
        $this->selector = $selector;
        $this->hashedToken = $hashedToken;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function setSelector(string $selector): self
    {
        $this->selector = $selector;

        return $this;
    }

    public function getHashedToken(): string
    {
        return $this->hashedToken;
    }

    public function setHashedToken(string $hashedToken): self
    {
        $this->hashedToken = $hashedToken;

        return $this;
    }

    public function getRequestedAt(): \DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTimeImmutable $requestedAt): self
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    public function getExpiredAt(): \DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeImmutable $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expiredAt->getTimestamp() <= time();
    }
}
