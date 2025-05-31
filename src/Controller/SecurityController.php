<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/change-password', name: 'app_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $form = $this->createForm(\App\Form\ChangePasswordForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
            if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                $form->get('oldPassword')->addError(new \Symfony\Component\Form\FormError('Current password is incorrect.'));
            } elseif ($newPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new \Symfony\Component\Form\FormError('New passwords do not match.'));
            } else {
                $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Password changed successfully.');
                return $this->redirectToRoute('app_home');
            }
        }
        return $this->render('security/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager, \Symfony\Component\Mailer\MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $entityManager->getRepository(\App\Entity\User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $resetRequest = new \App\Entity\PasswordResetRequest();
                $resetRequest->setUser($user);
                $resetRequest->setToken($token);
                $resetRequest->setRequestedAt(new \DateTimeImmutable());
                $resetRequest->setRequestIp($request->getClientIp());
                $entityManager->persist($resetRequest);
                $entityManager->flush();
                $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
                $emailMessage = (new \Symfony\Bridge\Twig\Mime\TemplatedEmail())
                    ->from('admin@domain.com')
                    ->to($user->getEmail())
                    ->subject('Password Reset Request')
                    ->htmlTemplate('security/reset_password_email.html.twig')
                    ->context(['resetUrl' => $resetUrl]);
                $mailer->send($emailMessage);
            }
            $this->addFlash('success', 'If your email exists in our system, a password reset link has been sent.');
            return $this->redirectToRoute('app_forgot_password');
        }
        return $this->render('security/forgot_password.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $resetRequest = $entityManager->getRepository(\App\Entity\PasswordResetRequest::class)->findValidToken($token);
        if (!$resetRequest) {
            $this->addFlash('error', 'Invalid or expired password reset link.');
            return $this->redirectToRoute('app_forgot_password');
        }
        $user = $resetRequest->getUser();
        $form = $this->createForm(\App\Form\ResetPasswordForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
            if ($newPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new \Symfony\Component\Form\FormError('Passwords do not match.'));
            } else {
                $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                $resetRequest->setResetAt(new \DateTimeImmutable());
                $resetRequest->setResetIp($request->getClientIp());
                $resetRequest->setSuccessful(true);
                $entityManager->persist($user);
                $entityManager->persist($resetRequest);
                $entityManager->flush();
                $this->addFlash('success', 'Your password has been reset. You can now log in.');
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('security/reset_password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
