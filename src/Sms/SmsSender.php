<?php

namespace App\Sms;

class SmsSender
{
    const TIME_FOR_SENDING = '12 minutes';
    const LOGIN = "Bylina";
    const PASSWORD = "5a985282";
    //const SENDER = "autoparus.by";
    const SENDER = "TEST-assist";
    const URL_SINGLE_SMS = "https://userarea.sms-assistent.by/api/v1/send_sms/plain";
    const URL_MULTIPLE_SMS = "https://userarea.sms-assistent.by/api/v1/json";

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $urlMultipleSMS;

    /**
     * @var string
     */
    private $urlSingleSMS;

    /**
     * @var string
     */
    private $sender;

    /**
     * SmsSender constructor.
     */
    public function __construct()
    {
        $this->login = self::LOGIN;
        $this->password = self::PASSWORD;
        $this->sender = self::SENDER;
        $this->urlMultipleSMS = self::URL_MULTIPLE_SMS;
        $this->urlSingleSMS = self::URL_SINGLE_SMS;
    }

    /**
     * @param $login
     * @param $password
     * @param $sender
     */
    public function setCredentials($login, $password, $sender)
    {
        $this->login = $login;
        $this->password = $password;
        $this->sender = $sender;
    }

    /**
     * @param array $params
     * @param \DateTime|null $sendingTime
     * @return mixed
     */
    public function sendSmsToMultipleNumbers(array $params, \DateTime $sendingTime = null)
    {
        $postdata = [
            'login' => $this->login,
            'password' => $this->password,
            'command' => 'sms_send',
            'message' => [
                'msg' => [
                    [
                        'recepient' => $params['phones'],
                        'sender' => $this->sender,
                        'validity_period' => 48,
                        'sms_text' => $this->getSmsMessage($params)
                    ],
                ]
            ]
        ];

        if (isset($sendingTime)) {
            $sendingTime = max(new \DateTime('+' . SmsSender::TIME_FOR_SENDING), $sendingTime);
            $dateSend = $sendingTime->format('YmdHi');
            $postdata['date_send'] = $dateSend;
        }

        $json_postdata = json_encode($postdata, JSON_UNESCAPED_UNICODE);
        $curl_result = $this->postContent($this->urlMultipleSMS, $json_postdata, $this->getMultipleMessageHeaders());
        $json_res = json_decode($curl_result['content'], true);

        return $json_res;
    }

    /**
     * @param array $params
     * @param \DateTime|null $sendingTime
     * @return mixed
     */
    public function sendSingleSms(array $params, \DateTime $sendingTime = null)
    {
        $postdata = [
            'user' => $this->login,
            'password' => $this->password,
            'recipient' => $params['phone'],
            'message' => $this->getSmsMessage($params),
            'sender' => $this->sender,
            'validity_period' => 48,
        ];

        if (isset($sendingTime)) {
            $sendingTime = max(new \DateTime('+' . SmsSender::TIME_FOR_SENDING), $sendingTime);
            $dateSend = $sendingTime->format('YmdHi');
            $postdata['date_send'] = $dateSend;
        }

        $curl_result = $this->postContent($this->urlSingleSMS, $postdata, $this->getSingleMessageHeaders());
        $json_res = json_decode($curl_result['content'], true);

        return $json_res;
    }

    /**
     * @param $url
     * @param $postdata
     *
     * @param $headers
     *
     * @return mixed
     */
    protected function postContent($url, $postdata, $headers)
    {
        $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

        $ch = curl_init( $url );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);

        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;

        return $header;
    }

    /**
     * @param array $params
     * @return string
     */
    private function getSmsMessage(array $params)
    {
        $message = null;

        switch ($params['template']) {
            case SmsNotifier::REGISTRATION_NOTIFICATION:
                $message = sprintf($params['template'], $params['link']);
                break;
        }

        return $message;
    }

    /**
     * @return array
     */
    private function getMultipleMessageHeaders()
    {
        $header = $this->getSingleMessageHeaders();

        $header[] = "Accept: text/json";
        $header[] = "Content-Type: text/json";

        return $header;
    }

    /**
     * @return array
     */
    private function getSingleMessageHeaders()
    {
        $header = [];

        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: utf-8";
        $header[] = "Pragma: ";

        return $header;
    }
}
