<?php

namespace App\Event\Event;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\EventDispatcher\Event;

final class EmailEvent extends Event
{
    private TemplatedEmail $templatedEmail;

    public function __construct(TemplatedEmail $templatedEmail)
    {
        $this->templatedEmail = $templatedEmail;
    }

    public function getEmail(): TemplatedEmail
    {
        return $this->templatedEmail;
    }
}
