<?php

namespace App\Sender;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Generator\PasswordGenerator;
use App\Sms\SmsNotifier;
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

    /** @var SmsNotifier */
    private $smsNotifier;

    /**
     * ForgotPasswordSender constructor.
     * @param PasswordGenerator $generator
     * @param EntityManagerInterface $em
     * @param Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param SmsNotifier $smsNotifier
     */
    public function __construct(
        PasswordGenerator $generator,
        EntityManagerInterface $em,
        Swift_Mailer $mailer,
        RouterInterface $router,
        SmsNotifier $smsNotifier
    )
    {
        $this->generator = $generator;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->smsNotifier = $smsNotifier;
    }


    public function createAndSendActivateCode(User $user)
    {
        $user = $this->createActivateCode($user);

        $link = $this->router->generate(
            'activate_user',
            ['code' => $user->getActivateCode()],
            UrlGeneratorInterface::ABSOLUTE_URL // This guy right here
        );

        $this->sendEmail($user, $link);
        $this->sendSms($user, $link);
    }

    protected function createActivateCode(User $user)
    {
        $code = $this->generator->generateNumberWordCode(8);

        $user->setActivateCode($code);

        $this->em->flush();

        return $user;
    }

    protected function sendEmail(User $user, $link)
    {
        $message = (new \Swift_Message('Активация'))
            ->setFrom('mr2@tut.by')
            ->setTo($user->getEmail())
            ->setBody(
                "Для активации аккаунта перейдите по ссылке: " . $link
            );

        $this->mailer->send($message);
    }

    protected function sendSms(User $user, $link)
    {
        $this->smsNotifier->sendSmsRegistration($user->getPhone(), $link);
    }
}