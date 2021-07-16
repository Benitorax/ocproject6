<?php

namespace App\Controller;

use App\Form\SnowboardTrickType;
use App\Service\SnowboardTrickManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SnowboardTrickController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->render('snowboard-trick/index.html.twig', [
        ]);
    }

    /**
     * @Route("/tricks/name", name="app_snowboard_trick_show")
     */
    public function show(): Response
    {
        return $this->render('snowboard-trick/show.html.twig', [
        ]);
    }

    /**
     * @Route("/tricks/create", name="app_snowboard_trick_create")
     */
    public function create(Request $request, SnowboardTrickManager $trickManager): Response
    {
        $form = $this->createForm(SnowboardTrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickManager->saveNewTrick($form->getData());
            $this->addFlash('success', 'A trick has been created with success!');

            return $this->redirectToRoute('app_snowboard_trick_create');
        }

        return $this->render('snowboard-trick/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tricks/name/edit", name="app_snowboard_trick_edit")
     */
    public function edit(): Response
    {
        return $this->render('snowboard-trick/edit.html.twig', [
        ]);
    }
}
