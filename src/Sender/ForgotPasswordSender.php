<?php

namespace App\Sender;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ForgotPasswordSender
{
    /** @var PasswordGenerator */
    private $generator;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Swift_Mailer */
    private $mailer;

    /** @var RouterInterface */
    private $router;

    /**
     * ForgotPasswordSender constructor.
     * @param PasswordGenerator $generator
     * @param EntityManagerInterface $em
     * @param Swift_Mailer $mailer
     * @param RouterInterface $router
     */
    public function __construct(PasswordGenerator $generator, EntityManagerInterface $em, Swift_Mailer $mailer, RouterInterface $router)
    {
        $this->generator = $generator;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->router = $router;
    }


    public function createAndSendForgotPassword(User $user)
    {
        $forgotPassword = $this->createForgotPassword($user);

        $this->sendEmail($forgotPassword);
    }

    protected function createForgotPassword($user)
    {
        $code = md5($this->generator->generateNumberWordCode(8));

        $forgotPassword = new ForgotPassword($code, $user);

        $this->em->persist($forgotPassword);
        $this->em->flush();

        return $forgotPassword;
    }

    protected function sendEmail(ForgotPassword $forgotPassword)
    {
        $link = $this->router->generate(
            'recovery_password',
            ['code' => $forgotPassword->getCode()],
            UrlGeneratorInterface::ABSOLUTE_URL // This guy right here
        );

        $message = (new \Swift_Message('Восстановление пароля'))
            ->setFrom($forgotPassword->getUser()->getEmail())
            ->setTo('bykovski.free@gmail.com')
            ->setBody(
                "Для восстановления пароля перейдите по ссылке: " . $link
            );

        $this->mailer->send($message);
    }
}