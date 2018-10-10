<?php

namespace App\Command;


use App\Entity\Admin;
use App\Entity\Client\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
// command example
    //php bin/console app:user:create Super "+375(11)111-11-11" password ROLE_SUPER_ADMINISTRATOR
    public function configure()
    {
        $this
            ->setName('app:user:create')
            ->setDescription('Create a new user')
            ->addArgument('name', InputArgument::REQUIRED, 'The username of the user')
            ->addArgument('email', InputArgument::REQUIRED, 'The username of the user')
            ->addArgument('phone', InputArgument::REQUIRED, 'The phone of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user')
            ->addArgument('role', InputArgument::REQUIRED, 'The user role');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.default_entity_manager');
        $passwordPlain = $input->getArgument('password');
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');
        $phone = $input->getArgument('phone');
        $role = $input->getArgument('role');

        $user = $this->createUser($role);

        $user->setName($name);
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setPhone($phone);
        $user->setEnabled(true);

        $password = $container->get('security.password_encoder')
            ->encodePassword($user, $passwordPlain);

        $user->setPassword($password);

        $em->persist($user);
        $em->flush();

        $output->writeln("<info>user with name={$name} and password={$passwordPlain}, role= {$role} has been successfully created</info>");
    }
    /**
     * @param $role
     * @return Admin|Client
     * @throws \Exception
     */
    private function createUser($role)
    {
        switch ($role) {
            case User::ROLE_ADMIN: return new Admin();
            case User::ROLE_CLIENT: return new Client();
            default: throw new \Exception('Unsupported role '. $role);
        }
    }
}