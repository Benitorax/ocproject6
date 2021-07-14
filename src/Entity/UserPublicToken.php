<?php

namespace App\Entity;

class UserPublicToken
{
    public $token;
    public $expiredAt;

    public function __construct(string $token, \DateTimeImmutable $expiredAt)
    {
        $this->token = $token;
        $this->expiredAt = $expiredAt;    
    }
}