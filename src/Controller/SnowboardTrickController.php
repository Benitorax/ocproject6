<?php

namespace App\Controller;

use App\Entity\SnowboardTrick;
use App\Form\SnowboardTrickType;
use App\Service\SnowboardTrickManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SnowboardTrickController extends AbstractController
{
    /**
     * Show the list of tricks.
     *
     * @Route("/tricks", name="app_snowboard_trick_index")
     */
    public function index(): Response
    {
        return $this->render('snowboard-trick/index.html.twig', [
        ]);
    }

    /**
     * Create a trick.
     *
     * @Route("/trick/new", name="app_snowboard_trick_create")
     */
    public function create(Request $request, SnowboardTrickManager $trickManager): Response
    {
        $form = $this->createForm(SnowboardTrickType::class);

        if ($this->getUser()) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $trickManager->saveNewTrick($form->getData());
                $this->addFlash('success', 'A trick has been created with success!');

                return $this->redirectToRoute('app_homepage');
            } elseif ($form->isSubmitted()) {
                $this->addFlash('danger', 'Please, correct any field errors.');
            }
        }

        return $this->render('snowboard-trick/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Show a detailed trick.
     *
     * @Route("/trick/{name}", name="app_snowboard_trick_show")
     */
    public function show(SnowboardTrick $trick): Response
    {
        return $this->render('snowboard-trick/show.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * Edit a trick.
     *
     * @Route("/trick/name/edit", name="app_snowboard_trick_edit")
     */
    public function edit(): Response
    {
        return $this->render('snowboard-trick/edit.html.twig', [
        ]);
    }
}
