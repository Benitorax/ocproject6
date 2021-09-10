<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class Emails
{
    /** @var TemplatedEmail[] */
    private $templatedEmails = [];

    /**
     * Return an array of TemplatedEmail objects.
     *
     * @return TemplatedEmail[]
     */
    public function getEmails()
    {
        return $this->templatedEmails;
    }

    /**
     * Add a TemplatedEmail object.
     */
    public function add(TemplatedEmail $email): void
    {
        $this->templatedEmails[] = $email;
    }
}
