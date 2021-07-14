<?php

namespace App\Entity;

class UserPublicToken
{
    public string $token;
    public \DateTimeImmutable $expiredAt;

    public function __construct(string $token, \DateTimeImmutable $expiredAt)
    {
        $this->token = $token;
        $this->expiredAt = $expiredAt;
    }
}
