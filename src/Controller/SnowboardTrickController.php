<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Service\CommentManager;
use App\Form\SnowboardTrickType;
use App\Repository\SnowboardTrickRepository;
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
    public function index(SnowboardTrickRepository $repository): Response
    {
        return $this->render('snowboard-trick/index.html.twig', [
            'tricks' => $repository->findAllTricks()
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
                $this->addFlash('danger', 'Please, correct any field with errors.');
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
    public function show(
        string $slug,
        Request $request,
        SnowboardTrickRepository $repository,
        CommentManager $commentManager
    ): Response {
        $trick = $repository->findOneWithRelation($slug);
        $form = $this->createForm(CommentType::class);

        if ($this->getUser()) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $commentManager->saveNewComment($form->getData(), $trick);
                $this->addFlash('success', 'Your comment has been submitted with success!');

                return $this->json([
                    'url' => $this->generateUrl('app_snowboard_trick_show', ['slug' => $slug])
                ], 303);
            } elseif ($form->isSubmitted()) {
                /** @var FormError */
                $error = $form->get('content')->getErrors()[0];

                return $this->json(['error' => $error->getMessage()], 422);
            }
        }

        return $this->render('snowboard-trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'queryString' => http_build_query($request->query->all()),
            'pagination' => $commentManager->getPagination(
                $trick,
                (int) $request->query->get('page') ?: 1
            )
        ]);
    }

    /**
     * Edit a trick.
     *
     * @Route("/trick/{slug}/edit", name="app_snowboard_trick_edit")
     */
    public function edit(
        string $slug,
        Request $request,
        SnowboardTrickRepository $trickRepository,
        SnowboardTrickManager $trickManager
    ): Response {
        $trick = $trickRepository->findOneWithRelation($slug);
        $form = $this->createForm(SnowboardTrickType::class, $trick);

        if ($this->getUser()) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $trickManager->saveEditedTrick($form->getData());
                $this->addFlash('success', 'The snowboard trick has been edited with success!');

                return $this->redirectToRoute('app_homepage');
            } elseif ($form->isSubmitted()) {
                $this->addFlash('danger', 'Please, correct any field with errors.');
            }
        }

        return $this->render('snowboard-trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

    /**
     * Delete a trick.
     *
     * @Route("/trick/{slug}/delete", methods={"POST"}, name="app_snowboard_trick_delete")
     */
    public function delete(string $slug, Request $request, SnowboardTrickManager $manager): Response
    {
        if ($this->isCsrfTokenValid('delete-trick', (string) $request->request->get('_token'))) {
            $manager->deleteTrickBySlug($slug);
            $this->addFlash('success', 'The snowboard trick has been deleted with success!');
        }

        return $this->redirectToRoute('app_snowboard_trick_index');
    }

    /**
     * Display a list of tricks.
     *
     * @Route("/api/trick", name="app_snowboard_trick_api")
     */
    public function apiTricks(Request $request, SnowboardTrickRepository $repository): Response
    {
        $content = $this->renderView('snowboard-trick/_tricks.html.twig', [
            'tricks' => $repository->findAllTricks((int) $request->query->get('index'))
        ]);

        return $this->json(['body' => $content]);
    }
}
