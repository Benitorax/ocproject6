<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignupType;
use App\Entity\UserToken;
use App\Service\UserManager;
use App\Service\UserTokenManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class UserController extends AbstractController
{
    /**
     * @Route("/sign-up", name="app_user_create")
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
     * @Route("/account/activate/{token}", name="app_user_activate")
     */
    public function activate(
        string $token,
        UserTokenManager $tokenManager,
        UserManager $userManager
    ): Response {
        try {
            $user = $tokenManager->validateTokenAndFetchUser(UserToken::SIGNUP, $token);
        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());

            return $this->redirectToRoute('app_user_create');
        }

        $userManager->activate($user);

        return $this->redirectToRoute('app_login');
    }
}
