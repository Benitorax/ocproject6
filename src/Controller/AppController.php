<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * Display the homepage.
     *
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('app_snowboard_trick_index');
    }
}
