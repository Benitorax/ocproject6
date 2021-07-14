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
     * @Route("/sign-up", name="user_create")
     */
    public function create(Request $request, UserManager $userManager): Response
    {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userManager->saveNewUser($user);
            $this->addFlash(
                'success', 
                'Thanks for registration, an confirmation email has been sent to your address.'
            );

            return $this->redirectToRoute('user_create');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/activate/{token}", name="user_activate")
     */
    public function activate(
        string $token, 
        UserTokenManager $tokenManager,
        UserManager $userManager
    ): Response
    {
        try {
            $user = $tokenManager->validateTokenAndFetchUser(UserToken::SIGNUP, $token);
        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());

            return $this->redirectToRoute('user_create');
        }
        
        $userManager->activate($user);

        return $this->redirectToRoute('login');
    }
}
