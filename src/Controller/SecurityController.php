<?php

namespace App\Controller;

use App\Entity\Client\Client;
use App\Entity\ForgotPassword;
use App\Entity\General\EmailDomain;
use App\Entity\General\RegistrationPage;
use App\Entity\User;
use App\Form\Type\ForgotPasswordType;
use App\Form\Type\RecoveryPasswordType;
use App\Form\Type\RegistrationType;
use App\Sender\ForgotPasswordSender;
use App\Sender\RegistrationSender;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function registrationAction(Request $request)
    {

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(["email" => "bykovski.free@gmail.com"]);

        if($user) {
            $em->remove($user);
            $em->flush();
        }

        if($this->getUser()){
            return $this->redirectToRoute("homepage");
        }

        $form = $this->createForm(RegistrationType::class, new Client());

        return $this->render('client/security/registration.html.twig', [
            "form" => $form->createView(),
            "isValid" => false,
            "page" => $this->getDoctrine()->getRepository(RegistrationPage::class)->findAll()[0],
        ]);
    }

    /**
     * @Route("/edit-registration-form", name="edit_registration_form")
     */
    public function editPersonalDataAction(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        RegistrationSender $sender
    )
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $isValid = false;

        $client = new Client();

        $form = $this->createForm(RegistrationType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($client, $client->getPassword());
            $client->setPassword($password);
            $client->setUsername($client->getEmail());
            $sender->createAndSendActivateCode($client);

            $em->persist($client);
            $em->flush();

            $isValid = true;
        }

        return $this->render('client/security/form/registration-form.html.twig', [
            "form" => $form->createView(),
            "isValid" => $isValid,
            "headline" => $this->getDoctrine()->getRepository(RegistrationPage::class)->findAll()[0]->getHeadline(),
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
     * @Route("/activate-user", name="activate_user")
     */
    public function activateUserAction(Request $request, TokenStorageInterface $tokenStorage)
    {
        $code = $request->query->get("code");
        $em = $this->getDoctrine()->getManager();

        if(!$code){
            return new Response('Данной ссылки не существует. Проверьте ссылку.', 404, ["Content-Type" => "text/html; charset=UTF-8"]);
        }

        $user = $em->getRepository(User::class)->findOneBy(["activateCode" => $code]);

        if(!($user instanceof User) || !$user->getActivateCode()){
            return new Response('Пользователь для активации не найден. Проверьте ссылку.', 404, ["Content-Type" => "text/html; charset=UTF-8"]);
        }

        $user->setActivateCode(null);
        $user->setActivatedAt(new DateTime());
        $user->setEnabled(true);

        $em->flush();
        $em->refresh($user);

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $tokenStorage->setToken($token);
        $request->getSession()->set('_security_main', serialize($token));

        return $this->redirectToRoute("show_user_office");
    }

    /**
     * @Route("/check-email-end", name="security_check_email_end")
     */
    public function checkEmailEndAction(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if(!isset($content["email"]) || strrpos($content["email"], '@') === false){
            return new JsonResponse(["exist" => false]);
        }

        $email = $content["email"];

        $domain = substr($email, strrpos($email, '@'));

        /** @var RegistrationPage $registrationPage */
        $registrationPage = $this->getDoctrine()->getRepository(RegistrationPage::class)->findAll()[0];

        $emailDomain = $registrationPage->findEmailDomain($domain);

        if($emailDomain instanceof EmailDomain){
            return new JsonResponse(["exist" => true, "domain" => $emailDomain->getDomain()]);
        }

        return new JsonResponse(["exist" => false]);
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