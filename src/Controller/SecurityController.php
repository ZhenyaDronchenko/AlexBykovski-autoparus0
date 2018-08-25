<?php

namespace App\Controller;

use App\Entity\Buyer;
use App\Form\Type\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $buyer = new Buyer();

        $form = $this->createForm(RegistrationType::class, $buyer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $encoder->encodePassword($buyer, $buyer->getPassword());
            $buyer->setPassword($password);
            $buyer->setUsername($buyer->getEmail());
            $buyer->setEnabled(true);

            $em->persist($buyer);
            $em->flush();

            return $this->redirectToRoute("login");
        }

        return $this->render('client/security/registration.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render(
            'client/security/login.html.twig',
            [
                'error'         => $error,
            ]
        );
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