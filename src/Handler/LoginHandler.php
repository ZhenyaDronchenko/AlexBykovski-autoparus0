<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class LoginHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var SessionInterface */
    private $session;

    /**
     * LoginHandler constructor.
     * 
     * @param EntityManagerInterface $em
     * @param EncoderFactoryInterface $encoderFactory
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface $session
     */
    public function __construct(
        EntityManagerInterface $em,
        EncoderFactoryInterface $encoderFactory,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    )
    {
        $this->em = $em;
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }


    public function getUserByLoginName($loginName)
    {
        $email = $loginName;
        $phone = preg_replace('/[^0-9]/', '', $loginName);

        $user = $this->em->getRepository(User::class)->findOneBy(["email" => $email]);

        if($user || !$phone){
            return $user;
        }

        return $this->em->getRepository(User::class)->findByOnlyNumbersPhone($phone);
    }

    public function isCorrectPassword(User $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
    }

    public function authorizeUser(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_main', serialize($token));

        return true;
    }
}