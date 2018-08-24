<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request)
    {
        return $this->render('client/security/registration.html.twig', [
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        return $this->render('client/security/login.html.twig', [
        ]);
    }

    /**
     * @Route("/forgot-password", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request)
    {
        return $this->render('client/security/forgot-password.html.twig', [
        ]);
    }

    /**
     * @Route("/recovery-password", name="recovery_password")
     */
    public function recoveryPasswordAction(Request $request)
    {
        return $this->render('client/security/recovery-password.html.twig', [
        ]);
    }

    /**
     * @Route("/success-recovery-password", name="success_recovery_password")
     */
    public function successRecoveryPasswordAction(Request $request)
    {
        return $this->render('client/security/success-recovery-password.html.twig', [
        ]);
    }
}