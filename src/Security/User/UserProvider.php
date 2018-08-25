<?php

namespace App\Security\User;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * UserProvider constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->em = $entityManager;
    }

    public function loadUserByUsername($login)
    {
        $user = $this->getUser($login);

        if(!($user instanceof UserInterface)){
            throw new UsernameNotFoundException(
                sprintf('User with email "%s", phone "%s" does not exist.', $login)
            );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }

    /**
     * @param $login
     * @return User|null
     */
    protected function getUser($login)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(["phone" => $login]);

        if($user instanceof User){
            return $user;
        }

        return $this->em->getRepository(User::class)->findOneBy(["email" => $login]);
    }
}