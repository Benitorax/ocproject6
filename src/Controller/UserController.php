<?php

namespace App\Controller;

use App\Form\SignupType;
use App\Entity\UserToken;
use App\Service\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Create a user.
     *
     * @Route("/registration", name="app_user_create")
     */
    public function create(Request $request, UserManager $userManager): Response
    {
        $form = $this->createForm(SignupType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->saveNewUser($form->getData());
            $this->addFlash(
                'success',
                'Thanks for registration, a confirmation email has been sent to your address.'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Activate a user.
     *
     * @Route("/account/activate/{token}", name="app_user_activate")
     */
    public function activate(
        string $token,
        UserManager $userManager
    ): Response {
        try {
            $user = $userManager->validateTokenAndFetchUser(UserToken::SIGNUP, $token);
        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());

            return $this->redirectToRoute('app_user_create');
        }

        $userManager->activate($user);
        $this->addFlash('success', 'Your account has been activated with success!');

        return $this->redirectToRoute('app_login');
    }
}
