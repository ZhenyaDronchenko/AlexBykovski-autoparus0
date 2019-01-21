<?php

namespace App\Sender;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RegistrationSender
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


    public function createAndSendActivateCode(User $user)
    {
        $this->createActivateCode($user);

        $this->sendEmail($user);
        //$this->sendSms($user);
    }

    protected function createActivateCode(User $user)
    {
        $code = md5($this->generator->generateNumberWordCode(16));

        $user->setActivateCode($code);

        $this->em->flush();

        return $user;
    }

    protected function sendEmail(User $user)
    {
        $link = $this->router->generate(
            'activate_user',
            ['code' => $user->getActivateCode()],
            UrlGeneratorInterface::ABSOLUTE_URL // This guy right here
        );

        $message = (new \Swift_Message('Активация'))
            ->setFrom('mr2@tut.by')
            ->setTo($user->getEmail())
            ->setBody(
                "Для активации своего аккаунта перейдите по ссылке: " . $link
            );

        $this->mailer->send($message);
    }
}