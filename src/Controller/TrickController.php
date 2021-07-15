<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->render('trick/index.html.twig', [
        ]);
    }

    /**
     * @Route("/tricks/name", name="app_trick_show")
     */
    public function show(): Response
    {
        return $this->render('trick/show.html.twig', [
        ]);
    }

    /**
     * @Route("/tricks/name/create", name="app_trick_create")
     */
    public function create(): Response
    {
        return $this->render('trick/create.html.twig', [
        ]);
    }

    /**
     * @Route("/tricks/name/edit", name="app_trick_edit")
     */
    public function edit(): Response
    {
        return $this->render('trick/edit.html.twig', [
        ]);
    }
}
