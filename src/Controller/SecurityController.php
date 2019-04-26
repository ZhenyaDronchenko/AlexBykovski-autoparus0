<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client\Client;
use App\Entity\ForgotPassword;
use App\Entity\General\EmailDomain;
use App\Entity\General\LoginPage;
use App\Entity\General\RecoveryPasswordPage;
use App\Entity\General\RegistrationPage;
use App\Entity\User;
use App\Form\Type\ForgotPasswordType;
use App\Form\Type\LoginType;
use App\Form\Type\RecoveryPasswordType;
use App\Form\Type\RegistrationType;
use App\Handler\ForgotPasswordHandler;
use App\Handler\LoginHandler;
use App\Sender\RegistrationSender;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        $registrationData = json_decode($request->cookies->get(RegistrationPage::SAVING_COOKIE_KEY), true);

        if($registrationData){
            $form->get("name")->setData($registrationData[RegistrationPage::NAME_COOKIE_KEY]);
            $form->get("email")->setData($registrationData[RegistrationPage::EMAIL_COOKIE_KEY]);
            $form->get("phone")->setData($registrationData[RegistrationPage::PHONE_COOKIE_KEY]);
            $form->get("isAgreeTerms")->setData($registrationData[RegistrationPage::TERMS_COOKIE_KEY]);
        }

        return $this->render('client/security/registration.html.twig', [
            "form" => $form->createView(),
            "isValid" => false,
            "page" => $this->getDoctrine()->getRepository(RegistrationPage::class)->findAll()[0],
        ]);
    }

//    /**
//     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
//     *
//     * @Route("/registration/as-user", name="registration_as_user")
//     */
//    public function registrationAsUserAction(
//        Request $request,
//        UserPasswordEncoderInterface $encoder,
//        TokenStorageInterface $tokenStorage)
//    {
//        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(["email" => "mr2@tut.by"]);
//        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
//        $tokenStorage->setToken($token);
//        $request->getSession()->set('_security_main', serialize($token));
//
//        return $this->redirectToRoute("homepage");
//    }

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
            $client->setEnabled(true);

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
     * @Route("/login-check", name="login_check")
     */
    public function loginAction(Request $request, LoginHandler $handler)
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        $page = $this->getDoctrine()->getRepository(LoginPage::class)->findAll()[0];

        if($form->isSubmitted() && $form->isValid()){
            $username = $form->get("username")->getData();
            $password = $form->get("password")->getData();

            $user = $handler->getUserByLoginName($username);

            if(!($user instanceof User)){
                return $this->render('client/security/form/login-form.html.twig',
                    [
                        'form' => $form,
                        'showModal1' => true,
                        'page' => $page,
                    ]
                );
            }

            if(!$handler->isCorrectPassword($user, $password)){
                return $this->render('client/security/form/login-form.html.twig',
                    [
                        'form' => $form,
                        'showModal2' => true,
                        'page' => $page,
                    ]
                );
            }

            if(!$user->isEnabled()) {
                return new JsonResponse([
                    "success" => true,
                    "redirect" => $this->generateUrl("login"),
                    'page' => $page,
                ]);
            }

            $handler->authorizeUser($user);

            if($user instanceof Admin){
                $redirectUrl = $this->generateUrl("sonata_admin_dashboard");
            }
            else{
                $redirectUrl = $this->generateUrl("show_user_office");
            }

            return new JsonResponse([
                "success" => true,
                "redirect" => $redirectUrl,
                'page' => $page,
            ]);
        }
        elseif ($form->isSubmitted()){
            return $this->render('client/security/form/login-form.html.twig', [
                'form' => $form,
                'page' => $page,
            ]);
        }
        else{
            $form->get("rememberMe")->setData(true);
        }

        return $this->render('client/security/login.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     *
     * @Route("/forgot-password", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request, LoginHandler $loginHandler, ForgotPasswordHandler $forgotPasswordHandler)
    {
        $page = $this->getDoctrine()->getRepository(RecoveryPasswordPage::class)->findAll()[0];
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get("email")->getData();
            $user = $loginHandler->getUserByLoginName($email);

            if(!($user instanceof User)){
                $form->get("email")->addError(new FormError("Пользователь с такими данными не найден"));
            }
            else{
                $type = $email === $user->getEmail() ? ForgotPassword::EMAIL_TYPE : ForgotPassword::PHONE_TYPE;

                $forgotPasswordHandler->createAndSendPassword($user, $type);

                return new JsonResponse(["success" => true]);
            }

            return $this->render('client/security/form/forgot-password-form.html.twig', [
                "form" => $form->createView(),
                "page" => $page,
            ]);
        }
        elseif($form->isSubmitted()){
            return $this->render('client/security/form/forgot-password-form.html.twig', [
                "form" => $form->createView(),
                "page" => $page,
            ]);
        }

        return $this->render('client/security/forgot-password.html.twig', [
            "form" => $form->createView(),
            "page" => $page,
        ]);
    }
}