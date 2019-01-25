<?php

namespace App\Handler;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Generator\PasswordGenerator;
use App\Sms\SmsNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class ForgotPasswordHandler
{
    /** @var PasswordGenerator */
    private $generator;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Swift_Mailer */
    private $mailer;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var SmsNotifier */
    private $smsNotifier;

    /**
     * ForgotPasswordSender constructor.
     * @param PasswordGenerator $generator
     * @param EntityManagerInterface $em
     * @param Swift_Mailer $mailer
     * @param EncoderFactoryInterface $encoderFactory
     * @param SmsNotifier $smsNotifier
     */
    public function __construct(
        PasswordGenerator $generator,
        EntityManagerInterface $em,
        Swift_Mailer $mailer,
        EncoderFactoryInterface $encoderFactory,
        SmsNotifier $smsNotifier
    )
    {
        $this->generator = $generator;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->encoderFactory = $encoderFactory;
        $this->smsNotifier = $smsNotifier;
    }


    public function createAndSendPassword(User $user, $type)
    {
        $newPassword = $this->changePassword($user);

        $forgotPassword = $this->createForgotPassword($user, $type);

        $this->sendMessage($user, $newPassword, $forgotPassword);
    }

    protected function changePassword(User $user)
    {
        $code = $this->generator->generateNumberWordCode(8);

        $encodedPassword = $this->encoderFactory->getEncoder($user)->encodePassword($code, $user->getSalt());
        $user->setPassword($encodedPassword);

        $this->em->flush();

        return $code;
    }

    protected function createForgotPassword(User $user, $type)
    {
        $forgotPassword = new ForgotPassword($user, $type);

        $this->em->persist($forgotPassword);
        $this->em->flush();

        return $forgotPassword;
    }

    protected function sendMessage(User $user, $newPassword, ForgotPassword $forgotPassword)
    {
        if($forgotPassword->getType() === ForgotPassword::PHONE_TYPE){
            $this->sendSms($user->getPhone(), $newPassword);
        }
        else{
            $this->sendEmail($user->getEmail(), $newPassword);
        }

        return true;
    }

    protected function sendSms($phone, $password)
    {
        $this->smsNotifier->sendSmsRecoveryPassword($phone, $password);
    }

    protected function sendEmail($email, $password)
    {
        $message = (new \Swift_Message('Восстановление пароля'))
            ->setFrom('mr2@tut.by')
            ->setTo($email)
            ->setBody(
                "Ваш новый пароль: " . $password
            );

        $this->mailer->send($message);
    }
}