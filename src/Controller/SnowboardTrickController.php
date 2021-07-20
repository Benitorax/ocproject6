<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Entity\SnowboardTrick;
use App\Service\CommentManager;
use App\Form\SnowboardTrickType;
use Symfony\Component\Form\FormError;
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
     * @Route("/trick/{slug}", name="app_snowboard_trick_show")
     */
    public function show(Request $request, SnowboardTrick $trick, CommentManager $commentManager): Response
    {
        $form = $this->createForm(CommentType::class);

        if ($this->getUser()) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $commentManager->saveNewComment($form->getData(), $trick);
                $this->addFlash('success', 'Your comment has been submitted with success!');

                return $this->json([
                    'url' => $this->generateUrl('app_snowboard_trick_show', ['slug' => $trick->getSlug()])
                ], 303);
            } elseif ($form->isSubmitted()) {
                /** @var FormError */
                $error = $form->get('content')->getErrors()[0];

                return $this->json(['error' => $error->getMessage()], 422);
            }
        }

        return $this->render('snowboard-trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
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
