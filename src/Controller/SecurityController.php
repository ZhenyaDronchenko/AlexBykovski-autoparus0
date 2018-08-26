<?php

namespace App\Controller;

use App\Entity\Buyer;
use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Form\Type\ForgotPasswordType;
use App\Form\Type\RecoveryPasswordType;
use App\Form\Type\RegistrationType;
use App\Sender\ForgotPasswordSender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
        $user = $this->getUser();

        if ($user instanceof User) {
            if($user->hasRole(User::ROLE_ADMIN)){
                return $this->redirectToRoute('sonata_admin_dashboard');
            }

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
    public function forgotPasswordAction(Request $request, ForgotPasswordSender $sender)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get("email")->getData();
            $user = $em->getRepository(User::class)->findOneBy(["email" => $email]);

            if(!($user instanceof User)){
                $form->get("email")->addError(new FormError("Пользователь с такими данными не найден, проверьте верно ли Вы ввели e-mail"));
            }
            else{
                $sender->createAndSendForgotPassword($user);
                $form = $this->createForm(ForgotPasswordType::class);

                return $this->redirectToRoute('success_recovery_password');
            }
        }

        return $this->render('client/security/forgot-password.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/recovery-password", name="recovery_password")
     */
    public function recoveryPasswordAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $code = $request->query->get("code");
        $em = $this->getDoctrine()->getManager();

        $forgotPassword = $em->getRepository(ForgotPassword::class)->findOneBy(["code" => $code]);

        if(!($forgotPassword instanceof ForgotPassword)){
            return $this->render('client/security/recovery-password.html.twig', [
                "incorrectCode" => true,
            ]);
        }

        $form = $this->createForm(RecoveryPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $forgotPassword->getUser();
            $password = $form->get("password")->getData();

            $encodedPassword = $encoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);

            $em->remove($forgotPassword);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('client/security/recovery-password.html.twig', [
            "form" => $form->createView(),
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