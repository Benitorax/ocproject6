<?php

namespace App\Controller;

use App\Entity\UserToken;
use App\Service\UserManager;
use App\Form\ResetPasswordType;
use App\Form\ResetPasswordRequestType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/forgot_password", name="app_password_request")
     */
    public function resetPasswordRequest(Request $request, UserManager $userManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->getData()['username'];
            $userManager->manageResetPasswordRequest($username);
            $this->addFlash(
                'success',
                sprintf(
                    'If you\'re registered with %s, an email has been sent to your address.',
                    $username
                )
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset_password/{token}", name="app_password_reset")
     */
    public function resetPassword(string $token, Request $request, UserManager $userManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        try {
            $user = $userManager->validateTokenAndFetchUser(UserToken::RESET_PASSWORD, $token);
        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());

            return $this->redirectToRoute('app_password_request');
        }

        $form = $this->createForm(ResetPasswordType::class, null, ['username' => $user->getUsername()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->getData()['password'];
            $userManager->manageResetPassword($user, $password);
            $this->addFlash('success', 'The password has been reset with success!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
