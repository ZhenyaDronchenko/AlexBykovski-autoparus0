<?php

namespace App\Sms;

use Doctrine\ORM\EntityManagerInterface;

class SmsNotifier
{
    const REGISTRATION_NOTIFICATION = "Для активации перейдите по ссылке: %s";

    /**
     * @var SmsSender
     */
    private $smsSender;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RecordSmsNotifier constructor.
     * @param SmsSender $smsSender
     * @param EntityManagerInterface $em
     */
    public function __construct(SmsSender $smsSender, EntityManagerInterface $em)
    {
        $this->smsSender = $smsSender;
        $this->em = $em;
    }

    /**
     * @param string $link
     * @param string $phone

     * @return mixed
     */
    public function sendSmsRegistration(string $phone, string $link)
    {
        $params = [
            "template" => self::REGISTRATION_NOTIFICATION,
            "link" => $link,
            "phone" => str_replace(' ', '', $phone),
        ];

        return $this->smsSender->sendSingleSms($params);
    }

    /**
     * @param $login
     * @param $password
     * @param $sender
     */
    public function setSenderCredentials($login, $password, $sender)
    {
        $this->smsSender->setCredentials($login, $password, $sender);
    }
}
