<?php

namespace App\Controller;

use App\Entity\Buyer;
use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Form\Type\ForgotPasswordType;
use App\Form\Type\RecoveryPasswordType;
use App\Form\Type\RegistrationType;
use App\Sender\ForgotPasswordSender;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
     * @Route("/registration", name="registration")
     */
    public function registrationAction(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage
    )
    {
        if($this->getUser()){
            return $this->redirectToRoute("homepage");
        }

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

            $token = new UsernamePasswordToken($buyer, null, 'main', $buyer->getRoles());
            $tokenStorage->setToken($token);
            $request->getSession()->set('_security_main', serialize($token));

            return $this->redirectToRoute("homepage");
        }

        return $this->render('client/security/registration.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render(
            'client/security/login.html.twig',
            [
                'error'         => $error,
            ]
        );
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
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
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
     * @Route("/recovery-password", name="recovery_password")
     */
    public function recoveryPasswordAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $code = $request->query->get("code");
        $em = $this->getDoctrine()->getManager();

        $forgotPassword = $em->getRepository(ForgotPassword::class)->findOneBy(["code" => $code]);

        if(!($forgotPassword instanceof ForgotPassword) || $forgotPassword->isExpiredCode()){
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
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
     * @Route("/success-recovery-password", name="success_recovery_password")
     */
    public function successRecoveryPasswordAction(Request $request)
    {
        return $this->render('client/security/success-recovery-password.html.twig', [
        ]);
    }
}